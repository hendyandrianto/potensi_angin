package com.potensiangin;

import java.io.InputStream;
import java.io.OutputStream;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.DialogInterface.OnClickListener;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.text.Html;
import android.util.Log;

public class Utils {
    public static void CopyStream(InputStream is, OutputStream os)
    {
        final int buffer_size=1024;
        try
        {
            byte[] bytes=new byte[buffer_size];
            for(;;)
            {
              int count=is.read(bytes, 0, buffer_size);
              if(count==-1)
                  break;
              os.write(bytes, 0, count);
            }
        }
        catch(Exception ex){}
    }
    public static void showYesNoPrompt(final Activity _context, final String title, 
			final String message, final OnClickListener onYesListener, 
			final OnClickListener onNoListener) {
		_context.runOnUiThread(new Runnable(){
			public void run(){
				AlertDialog.Builder builder = new AlertDialog.Builder(_context);
				builder.setTitle(title);
				builder.setIcon(android.R.drawable.ic_dialog_info);
				builder.setMessage(message);
				builder.setCancelable(false);
				builder.setPositiveButton("Ya", onYesListener);
				builder.setNegativeButton("Tidak", onNoListener);
				builder.show();
			};
		});
	}
	
	public static void showErrorMessage(Activity _context, String message) {
		showMessage(_context, "Error!", message, android.R.drawable.ic_dialog_alert, null);
	}
	
	public static void showInfoMessage(Activity _context, String message) {
		showMessage(_context, "Informasi", message, android.R.drawable.ic_dialog_info, null);
	}

	public static void showFatalErrorMessage(final Activity _context, String message) {
		showMessage(_context, "Error!", message, android.R.drawable.ic_dialog_alert, new DialogInterface.OnClickListener() {
			@Override
			public void onClick(DialogInterface dialog, int which) {
				(_context).finish();
			}
		});
	}
	
	public static void showMessage(final Activity _context, final String title, final String message, final int icon, final DialogInterface.OnClickListener ackHandler) {
		_context.runOnUiThread(new Runnable(){
			public void run(){
				AlertDialog.Builder builder = new AlertDialog.Builder(_context);
				builder.setTitle(title);
				builder.setMessage(Html.fromHtml(message));
				builder.setCancelable(false);
				builder.setPositiveButton("Oke", ackHandler);
				builder.setIcon(icon);
				builder.show();
			}
		});
	}
	
	public static void log(String m){
		Log.d("pakargigi", m);
	}
	
	public static boolean isConnected(Context context) {
	    ConnectivityManager cm = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
	    NetworkInfo netInfo = cm.getActiveNetworkInfo();
	    if (netInfo != null && netInfo.isConnected()) {
	        return true;
	    }
	    return false;
	}
}