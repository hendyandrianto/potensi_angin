package com.potensiangin;


import java.util.ArrayList;
import java.util.HashMap;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import android.net.Uri;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GooglePlayServicesUtil;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.GoogleMap.OnMarkerClickListener;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;

import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.location.Criteria;
import android.location.Location;
import android.location.LocationManager;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ListView;
import android.widget.Toast;

public class ListTempat extends FragmentActivity implements android.location.LocationListener, OnMarkerClickListener {
	final int RQS_GooglePlayServices = 1;
	private GoogleMap googleMap;

	double latitude, longitude;
	ProgressDialog pDialog;

	ArrayList<HashMap<String, String>> dataList = new ArrayList<HashMap<String, String>>();


	JSONArray college = null;
	ListView lve;
	Button list_dokter, refresh;

	ConnectionDetector cd;
	Boolean isInternetPresent = false;

	AlertDialogManager alert = new AlertDialogManager();

	HashMap<String, String> map;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.tempat_view);

		cekInternet();

		SupportMapFragment fm = (SupportMapFragment) getSupportFragmentManager()
				.findFragmentById(R.id.map);

		googleMap = fm.getMap();

		googleMap.setMyLocationEnabled(true);

		CekGPS();

		refresh = (Button) findViewById(R.id.booking);

		refresh.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub
				cekInternet();
			}
		});
	}

	public class AmbilData extends AsyncTask<String, String, String> {

		@Override
		protected void onPreExecute() {
			super.onPreExecute();
			pDialog = new ProgressDialog(ListTempat.this);
			pDialog.setMessage("Loading Data ...");
			pDialog.setIndeterminate(false);
			pDialog.setCancelable(true);
			pDialog.show();
		}

		@Override
		protected String doInBackground(String... arg0) {
			
			String url;

			url = "http://angin.coder-01.com/api/get_tempat";

			JSONMap jParser = new JSONMap();

			JSONObject json = jParser.getJSONFromUrl(url);
			try {
				college = json.getJSONArray("list_tempat");
				Log.e("error", json.getString("success"));

				for (int i = 0; i <= college.length(); i++) {
					
					JSONObject c = college.getJSONObject(i);
					
					map = new HashMap<String, String>();

					String id_1 = c.getString("id").trim();
					String latitude_1 = c.getString("lat").trim();
					String longitude_1 = c.getString("lon").trim();
					String nama_1 = c.getString("nama_daerah").trim();
					String cuaca_1 = c.getString("cuaca").trim();
					String temperatur_1 = c.getString("temperatur").trim();
					String kecepatan_1 = c.getString("kecepatan").trim();
					
					
					map.put("id", id_1);
					map.put("nama", nama_1);
					map.put("latitude", latitude_1);
					map.put("longitude", longitude_1);
					map.put("cuaca", cuaca_1);
					map.put("temperatur", temperatur_1);
					map.put("kecepatan", kecepatan_1);

					dataList.add(map);

				}

			} catch (JSONException e) {
				Log.e("error", "Teu Bisa Di Tarik");
			}

			return null;
		}

		@Override
		protected void onPostExecute(String result) {
			// TODO Auto-generated method stub
			super.onPostExecute(result);
			pDialog.dismiss();

			for (int x = 0; x < dataList.size(); x = x + 1) {
				double latasal = Double.parseDouble(dataList.get(x).get(
						"latitude"));
				double longasal = Double.parseDouble(dataList.get(x).get(
						"longitude"));
				LatLng posisi = new LatLng(latasal, longasal);
				String nama = dataList.get(x).get("nama");
				String cuaca = dataList.get(x).get("cuaca");
				String temperatur = dataList.get(x).get("temperatur");
				String kecepatan = dataList.get(x).get("kecepatan");
				
				googleMap.addMarker(new MarkerOptions()
						.position(posisi)
						.title(nama + "\n" + cuaca + "\n" + temperatur + "\n" + kecepatan + "\n")
						.icon(BitmapDescriptorFactory
								.fromResource(R.drawable.marker_icon)));
			}
		}
	}


	@Override
	public void onLocationChanged(Location location) {
		// TODO Auto-generated method stub
		try {
			latitude = location.getLatitude();
			longitude = location.getLongitude();
		} catch (Exception e) {
			// TODO: handle exception
		}
	}

	public void CekGPS() {
		try {
			LocationManager manager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
			if (!manager.isProviderEnabled(LocationManager.GPS_PROVIDER)) {
				AlertDialog.Builder builder = new AlertDialog.Builder(this);
				builder.setTitle("Informasi GPS");
				builder.setMessage("Apakah anda akan mengaktifkan GPS?");
				builder.setPositiveButton("Ya",
						new DialogInterface.OnClickListener() {

							@Override
							public void onClick(DialogInterface arg0, int arg1) {
								// TODO Auto-generated method stub
								Intent i = new Intent(
										android.provider.Settings.ACTION_LOCATION_SOURCE_SETTINGS);
								startActivity(i);

							}
						});
				builder.setNegativeButton("Tidak",
						new DialogInterface.OnClickListener() {

							@Override
							public void onClick(DialogInterface dialog, int arg1) {
								// TODO Auto-generated method stub
								dialog.dismiss();
							}
						});
				builder.create().show();
			}
		} catch (Exception e) {
			// TODO: handle exception

		}
		int status = GooglePlayServicesUtil
				.isGooglePlayServicesAvailable(getBaseContext());
		if (status != ConnectionResult.SUCCESS) {
			int requestCode = 10;
			Dialog dialog = GooglePlayServicesUtil.getErrorDialog(status, this,
					requestCode);
			dialog.show();
		} else {
			
			Criteria criteria = new Criteria();
			LocationManager locationmanager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
			String provider = locationmanager.getBestProvider(criteria, true);
			Location location = locationmanager.getLastKnownLocation(provider);
			
			if (location != null) {
				onLocationChanged(location);
			}
			
			locationmanager.requestLocationUpdates(provider, 500, 0, this);
			LatLng posisi = new LatLng(latitude, longitude);
			
			googleMap.animateCamera(CameraUpdateFactory.newLatLngZoom(posisi,
					12));
			googleMap.setOnMarkerClickListener(this);
		}
	}

	@Override
	public void onProviderDisabled(String provider) {
		// TODO Auto-generated method stub

	}

	@Override
	public void onProviderEnabled(String provider) {
		// TODO Auto-generated method stub

	}

	@Override
	public void onStatusChanged(String provider, int status, Bundle extras) {
		// TODO Auto-generated method stub

	}

	public void cekInternet() {
		cd = new ConnectionDetector(getApplicationContext());
		isInternetPresent = cd.isConnectingToInternet();

		if (isInternetPresent) {

			new AmbilData().execute();

		} else {

			alert.showAlertDialog(ListTempat.this, "Peringatan",
					"Cek Koneksi Internet", false);
		}
	}
	@Override
	public boolean onMarkerClick(Marker marker) {
		// TODO Auto-generated method stub
		
		String id= marker.getId();
		id = id.substring(1);
		Toast.makeText(getApplicationContext(),"Nama Daerah : " + dataList.get(Integer.parseInt(id)).get("nama")+"\n"+"Cuaca : " + dataList.get(Integer.parseInt(id)).get("cuaca")+"\n"+"Temperatur : " + dataList.get(Integer.parseInt(id)).get("temperatur")+"\n"+"Kecepatan Angin : " + dataList.get(Integer.parseInt(id)).get("kecepatan"), Toast.LENGTH_LONG).show();

		return false;
	}
//	@Override
//	   public boolean onMarkerClick(Marker arg0) {
//	    // TODO Auto-generated method stub
//	    
//	    try {
//	     StringBuilder urlString = new StringBuilder();         
//	     String daddr = (String.valueOf(arg0.getPosition().latitude)+","+String.valueOf(arg0.getPosition().longitude)); 
//	     urlString.append("http://maps.google.com/maps?f=d&hl=en"); 
//	     urlString.append("&saddr="+String.valueOf(googleMap.getMyLocation().getLatitude())+","+String.valueOf(googleMap.getMyLocation().getLongitude())); 
//	     urlString.append("&daddr="+daddr);
//	     Intent i = new Intent(Intent.ACTION_VIEW, Uri.parse(urlString.toString()));
//	     startActivity(i);
//	    } catch (Exception ee) {
//	     Toast.makeText(getApplicationContext(), "Lokasi Saat Ini Belum Didapatkan, Coba Nyalakan GPS, Keluar Ruangan dan Tunggu Beberapa Saat", Toast.LENGTH_LONG).show();
//	    }
//	    return false;
//	   }
//	

}
