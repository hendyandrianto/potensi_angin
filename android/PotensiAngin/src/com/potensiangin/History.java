package com.potensiangin;

import java.util.ArrayList;
import java.util.HashMap;

import org.json.JSONArray;
import org.json.JSONObject;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.AdapterView.OnItemClickListener;

public class History extends Activity {
	ListView lve;
	String nama,url,idxx,success;
	TextView idna;
	ConnectionDetector cd;
	Boolean isInternetPresent = false;
	AlertDialogManager alert = new AlertDialogManager();
	Button refresh;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.history_view);
		url = "http://angin.coder-01.com/api/list_daerah";
		cekInternet();
		lve = (ListView) findViewById(R.id.list);
		lve.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View view,
					int position, long id) {
				// TODO Auto-generated method stub
				
				idna = ((TextView) view.findViewById(R.id.txt_id));
				idxx = idna.getText().toString();
				
				Intent x = new Intent(getApplicationContext(), DetilDaerah.class);
				x.putExtra("idna", idxx);
				startActivity(x);
				
				
			}
		});
		refresh =(Button)findViewById(R.id.reload);
		refresh.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub
				cekInternet();
			}
		});
	}	
	public void cekInternet() {
		cd = new ConnectionDetector(getApplicationContext());
		isInternetPresent = cd.isConnectingToInternet();
		if (isInternetPresent) {
			new GetDaerah().execute();
		} else {
			alert.showAlertDialog(History.this, "Peringatan",
					"Cek Koneksi Internet", false);
		}
	}
	public class GetDaerah extends AsyncTask<String, String, String> {
		ArrayList<HashMap<String, String>> dataList = new ArrayList<HashMap<String, String>>();
		ProgressDialog pDialog;
		
		@Override
		protected void onPreExecute() {
			// TODO Auto-generated method stub
			super.onPreExecute();
			pDialog = new ProgressDialog(History.this);
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
						
						String nama_daerah = c.getString("nama_daerah").trim();
						String id = c.getString("id").trim();
						String provinsi = c.getString("provinsi").trim();
						String kota = c.getString("kota").trim();
						String kecamatan = c.getString("kecamatan").trim();
						String kelurahan = c.getString("kelurahan").trim();
						String lon = c.getString("lon").trim();
						String lat = c.getString("lat").trim();
						
						data.put("nama_daerah", nama_daerah);
						data.put("provinsi", provinsi);
						data.put("kota", kota);
						data.put("kecamatan", kecamatan);
						data.put("kelurahan", kelurahan);
						data.put("lon", lon);
						data.put("lat", lat);
						data.put("id", id);
						dataList.add(data);

					}
				} else {
					Log.e("error", "List Daerah Tidak Ditemukan");
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
				Toast.makeText(getApplicationContext(), "Tidak Ada List Daerah", Toast.LENGTH_LONG).show();
				finish();
			} 
			ListAdapter adapter = new SimpleAdapter(getApplicationContext(),
					dataList, R.layout.daerah_list, new String[] { "id","nama_daerah" }, new int[] {R.id.txt_id,R.id.txt_nama_daerah});
			lve.setAdapter(adapter);
		}		
	}

}
