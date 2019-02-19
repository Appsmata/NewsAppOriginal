package powerrender.setting;

import android.app.Activity;
import android.content.Context;
import android.util.DisplayMetrics;
import android.view.Display;

import powerrender.screen.MainActivity;

/**
 * Created by SUPRIYANTO on 02/08/2018.
 */

public class ToolsUtils {

    public static MainActivity mainActivity;

    public ToolsUtils(Context context){
        this.mainActivity = (MainActivity) context;
    }

    public static int getFeaturedNewsImageHeight(Activity activity){
        float width_ratio = 2, height_ratio = 1;
        Display display = activity.getWindowManager().getDefaultDisplay();
        DisplayMetrics displayMetrics = new DisplayMetrics();
        display.getMetrics(displayMetrics);
        float screenWidth = displayMetrics.widthPixels - 10;
        float resHeight = (screenWidth * height_ratio) / width_ratio;
        return Math.round(resHeight);
    }
}
