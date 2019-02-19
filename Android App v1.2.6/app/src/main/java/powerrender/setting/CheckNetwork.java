package powerrender.setting;

import android.content.Context;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;

/**
 * Created by SUPRIYANTO on 06/08/2018.
 */

public class CheckNetwork {
    public static boolean isConnectCheck(Context context){
        try {
            ConnectivityManager cm = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
            NetworkInfo networkInfo = cm.getActiveNetworkInfo();
            if (networkInfo != null){
                if (networkInfo.isConnected() || networkInfo.isConnectedOrConnecting()){
                    return true;
                }else {
                    return false;
                }

            }else {
                return false;
            }

        }catch (Exception e){
            return false;
        }
    }
}
