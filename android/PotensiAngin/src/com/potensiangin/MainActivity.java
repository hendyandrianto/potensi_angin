package com.potensiangin;

import android.os.Bundle;
import android.app.Activity;
import android.content.Intent;
public class MainActivity extends Activity{
		
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main); 

		Thread timer = new Thread() { 
			public void run() {
				try {
					sleep(4000); 
					
				} catch (Exception e) {
					// TODO: handle exception
					e.printStackTrace();
				} finally {
						Intent i = new Intent(MainActivity.this, MenuUtama.class);
						startActivity(i);
						finish(); 
				}
			}
		};
		timer.start(); 
	}
}