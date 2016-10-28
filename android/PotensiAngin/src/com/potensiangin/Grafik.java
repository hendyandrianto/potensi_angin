package com.potensiangin;

import com.potensiangin.History.GetDaerah;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ListView;
import android.widget.Toast;
import android.webkit.WebView;
import android.webkit.WebViewClient;

public class Grafik extends Activity {
	String url;
	ListView lve;
	private WebView mWvDroidMu;
	ConnectionDetector cd;
	Button refresh;
	Boolean isInternetPresent = false;
	AlertDialogManager alert = new AlertDialogManager();
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		cekInternet();
	}
	public void cekInternet() {
		cd = new ConnectionDetector(getApplicationContext());
		isInternetPresent = cd.isConnectingToInternet();
		if (isInternetPresent) {
			Intent i = getIntent();
			mWvDroidMu = new WebView(this);
			mWvDroidMu.getSettings().setJavaScriptEnabled(true); // enable javascript
			final Activity activity = this;
			mWvDroidMu.setWebViewClient (new WebViewClient());
		 	mWvDroidMu .loadUrl("http://angin.coder-01.com/api/get_grafik/"+i.getStringExtra("idna"));
		 	setContentView(mWvDroidMu );
		 	
		} else {
			alert.showAlertDialog(Grafik.this, "Peringatan",
					"Cek Koneksi Internet", false);
		}
	}

}
