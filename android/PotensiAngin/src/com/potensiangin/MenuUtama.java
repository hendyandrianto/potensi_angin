package com.potensiangin;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;

public class MenuUtama extends Activity {
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.menu_utama);
	}
	public void help (View theButton){
    	Intent a = new Intent(this,CaraPengguna.class);
    	startActivity(a);
    }
	public void about (View theButton){
    	Intent a = new Intent(this,About.class);
    	startActivity(a);
    }
	public void history (View theButton){
    	Intent a = new Intent(this,History.class);
    	startActivity(a);
    }
	public void tempat (View theButton){
    	Intent a = new Intent(this,ListTempat.class);
    	startActivity(a);
    }
		
}
