package com.potensiangin;

import java.lang.ref.SoftReference;
import java.util.Collections;
import java.util.HashMap;
import java.util.Map;
import android.graphics.Bitmap;
import android.util.Log;

public class MemoryCache {
    private Map<String, SoftReference<Bitmap>> cache=Collections.synchronizedMap(new HashMap<String, SoftReference<Bitmap>>());
    
    public Bitmap get(String url){
        if(!cache.containsKey(url))
            return null;
        SoftReference<Bitmap> ref=cache.get(url);
        return ref.get();
    }
    
    public void put(String url, Bitmap bitmap){
        cache.put(url, new SoftReference<Bitmap>(bitmap));
    }

    public void clear() {
        cache.clear();
    }
}