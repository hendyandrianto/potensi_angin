package com.potensiangin;

import java.util.ArrayList;
import java.util.HashMap;
import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Context;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

@SuppressLint("InflateParams")
public class LazyAdapter extends BaseAdapter {
    
    private Activity activity;
    private ArrayList<HashMap<String, String>> data;
    private static LayoutInflater inflater=null;
    public ImageLoader imageLoader; 
    
    public LazyAdapter(Activity a, ArrayList<HashMap<String, String>> d) {
        activity = a;
        data=d;
        inflater = (LayoutInflater)activity.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        imageLoader=new ImageLoader(activity.getApplicationContext());
    }

    public int getCount() {
        return data.size();
    }

    public Object getItem(int position) {
        return position;
    }

    public long getItemId(int position) {
        return position;
    }
    public View getView(int position, View convertView, ViewGroup parent) {
    	 View vi=convertView;
         if(convertView==null)
        	
            vi = inflater.inflate(R.layout.informasi_list, null);
         
        	TextView waktu = (TextView)vi.findViewById(R.id.txt_waktu);
        	TextView cuaca = (TextView)vi.findViewById(R.id.txt_cuaca);
	        TextView temperatur = (TextView)vi.findViewById(R.id.txt_temperatur); 
	        TextView kecepatan = (TextView)vi.findViewById(R.id.txt_kecepatan);
	        ImageView gambar=(ImageView)vi.findViewById(R.id.foto);
	        
	        HashMap<String, String> list_info = new HashMap<String, String>();
	        list_info = data.get(position);

	        cuaca.setText(list_info.get(DetilDaerah.in_cuaca));
	        temperatur.setText(list_info.get(DetilDaerah.in_temperatur)+" °C");
	        kecepatan.setText(list_info.get(DetilDaerah.in_kecepatan)+" (m/s)");
	        waktu.setText(list_info.get(DetilDaerah.in_waktu));
	        imageLoader.DisplayImage(list_info.get(DetilDaerah.in_gambar), gambar);
	        return vi;

	        
    }
}