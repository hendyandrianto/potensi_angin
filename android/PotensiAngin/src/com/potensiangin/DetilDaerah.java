package com.potensiangin;

import java.util.ArrayList;
import java.util.HashMap;

import org.json.JSONArray;
import org.json.JSONObject;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

public class DetilDaerah extends Activity {
	ListView lve,list;
    LazyAdapter adapter;
	String nama,url,idxx,success;
	TextView idna;
	static String in_id= "id";
	static String in_cuaca= "cuaca";
	static String in_temperatur= "temperatur";
	static String in_kecepatan= "kecepatan";
	static String in_waktu= "waktu";
	static String in_gambar= "foto";
	
	ConnectionDetector cd;
	Boolean isInternetPresent = false;
	AlertDialogManager alert = new AlertDialogManager();
	Button refresh,grafik;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.detil_daerah);	
		Intent i = getIntent();
		lve = (ListView) findViewById(R.id.list);
		url = "http://angin.coder-01.com/api/get_daerah/"+i.getStringExtra("idna");
		cekInternet();
		refresh =(Button)findViewById(R.id.reload);
		refresh.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub
				cekInternet();
			}
		});
		grafik =(Button)findViewById(R.id.grafik);
		grafik.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub
				Intent i = getIntent();
				Intent intent = new Intent(DetilDaerah.this, Grafik.class);
	        	intent.putExtra("idna",i.getStringExtra("idna"));
				startActivity(intent);
			}
		});
	}
	public void cekInternet() {
		cd = new ConnectionDetector(getApplicationContext());
		isInternetPresent = cd.isConnectingToInternet();
		if (isInternetPresent) {
			new CekDataDaerah().execute();
		} else {
			alert.showAlertDialog(DetilDaerah.this, "Peringatan",
					"Cek Koneksi Internet", false);
		}
	}
	public class CekDataDaerah extends AsyncTask<String, String, String> {
		ArrayList<HashMap<String, String>> dataList = new ArrayList<HashMap<String, String>>();
		ProgressDialog pDialog;
		
		@Override
		protected void onPreExecute() {
			// TODO Auto-generated method stub
			super.onPreExecute();
			pDialog = new ProgressDialog(DetilDaerah.this);
			pDialog.setMessage("Loading Data ...");
			pDialog.setIndeterminate(false);
			pDialog.setCancelable(true);
			pDialog.show();
		}
		@Override
		protected String doInBackground(String... arg0) {
			JSONParser jParser = new JSONParser();
			JSONObject json = jParser.getJSONFromUrl(url);
			try {
				success = json.getString("success");
				Log.e("error", "nilai sukses=" + success);
				JSONArray hasil = json.getJSONArray("data_daerah");
				if (success.equals("1")) {
					for (int i = 0; i < hasil.length(); i++) {
						JSONObject c = hasil.getJSONObject(i);
						HashMap<String, String> data = new HashMap<String, String>();
						
						data = new HashMap<String, String>();
						
						String id = c.getString("id").trim();
						String cuaca = c.getString("cuaca").trim();
						String temperatur = c.getString("temperatur").trim();
						String kecepatan = c.getString("kecepatan").trim();
						String waktu= c.getString("waktu").trim();
						String gambar= c.getString("foto").trim();
						
						data.put(in_id, id);
						data.put(in_cuaca, cuaca);
						data.put(in_temperatur, temperatur);
						data.put(in_kecepatan, kecepatan);
						data.put(in_waktu, waktu);
						data.put(in_gambar, gambar);
						
						dataList.add(data);

					}
				} else {
					Log.e("error", "Informasi Data Daerah Belum Tersedia");
				}

			} catch (Exception e) {
				// TODO: handle exception
				Log.e("error", "tidak bisa ambil data 1");
			}
			return null;
			
		}
		@Override
		protected void onPostExecute(String result) {
			// TODO Auto-generated method stub
			super.onPostExecute(result);
			pDialog.dismiss();
			if (success.equals("2")) {	
				Toast.makeText(getApplicationContext(), "Informasi Data Daerah Tidak Tersedia", Toast.LENGTH_LONG).show();
			}
			list=(ListView)findViewById(R.id.list);
			adapter=new LazyAdapter(DetilDaerah.this, dataList);        
			list.setAdapter(adapter);
		}		
	}

}
