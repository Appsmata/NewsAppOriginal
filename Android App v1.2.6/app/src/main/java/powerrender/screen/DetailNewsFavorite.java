package powerrender.screen;

import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.drawable.Drawable;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.support.v4.app.Fragment;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.Menu;
import android.view.MenuItem;
import android.webkit.WebView;
import android.widget.ImageView;
import android.widget.TextView;

import com.squareup.picasso.Picasso;
import com.squareup.picasso.Target;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;

import powerrender.db.DatabaseHelpers;
import powerrender.retrofitconfig.BaseUrlConfig;

public class DetailNewsFavorite extends AppCompatActivity {

    private TextView titleDetailsNews;
    private WebView webView;
    private ImageView imgDetailNew;
    private Context context;
    private DatabaseHelpers databaseHelpers;
    private String storeTitle, storeShortTitle, storeImg, descriptionStore, datenews;
    int storeId;
    private MenuItem menuItem;
    private Toolbar toolbar;
    private ActionBar actionBar;
    private Fragment fragment;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_news_favorite);

        titleDetailsNews = (TextView) findViewById(R.id.titleDetailsNews);
        webView = (WebView) findViewById(R.id.descriptionFavorites);
        imgDetailNew = (ImageView) findViewById(R.id.imageFavorites);
        databaseHelpers = new DatabaseHelpers(this);
        toolbarSet();
        Intent getValue = getIntent();

        storeId = getValue.getIntExtra("menu_id", 0);
        storeTitle = getValue.getStringExtra("menu_title");
        storeShortTitle = getValue.getStringExtra("short_title");
        storeImg = getValue.getStringExtra("menu_image");
        descriptionStore = getValue.getStringExtra("description");
        String html = "<style>img{display: inline; height: auto; max-width: 100%;}</style>";

        titleDetailsNews.setText(storeTitle);
        Picasso.with(context).load(BaseUrlConfig.BASE_URL+storeImg).into(imgDetailNew);
        webView.loadDataWithBaseURL(null, html+descriptionStore, "text/html", "UTF-8", null);

    }

    private void toolbarSet() {
        toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        actionBar = getSupportActionBar();
        actionBar.setDisplayHomeAsUpEnabled(true);
        actionBar.setHomeButtonEnabled(true);
        actionBar.setTitle("");
    }

    public void shareItem(String url, final String text) {
        Picasso.with(getApplicationContext()).load(url).into(new Target() {
            @Override public void onBitmapLoaded(Bitmap bitmap, Picasso.LoadedFrom from) {
                Intent i = new Intent(Intent.ACTION_SEND);
                i.setType("image/*");
                i.putExtra(Intent.EXTRA_TEXT, text);
                i.putExtra(Intent.EXTRA_STREAM, getLocalBitmapUri(bitmap));
                startActivity(Intent.createChooser(i, "Share Image"));
                overridePendingTransition(R.anim.animation_blank, R.anim.animation_left_to_right);
            }
            @Override public void onBitmapFailed(Drawable errorDrawable) { }
            @Override public void onPrepareLoad(Drawable placeHolderDrawable) { }
        });
    }

    private Uri getLocalBitmapUri(Bitmap bitmap) {
        Uri bmpUri = null;
        try {
            File file =  new File(getExternalFilesDir(Environment.DIRECTORY_PICTURES), "share_image_" + System.currentTimeMillis() + ".png");
            FileOutputStream out = new FileOutputStream(file);
            bitmap.compress(Bitmap.CompressFormat.PNG, 90, out);
            out.close();
            bmpUri = Uri.fromFile(file);
        } catch (IOException e) {
            e.printStackTrace();
        }
        return bmpUri;
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.favorite_news_detail, menu);
        menuItem = menu.findItem(R.id.deleteOne);
        if (databaseHelpers.isDataExist(storeId)){
            menuItem.setIcon(R.drawable.ic_favorite_black_48dp);
        }
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int item_variable = item.getItemId();
        if (item_variable == android.R.id.home){
            onBackPressed();
        }else if (item_variable == R.id.deleteOne){
            if (databaseHelpers.isDataExist(storeId)){
                databaseHelpers.deleteData(storeId);
                menuItem.setIcon(R.drawable.ic_favorite_border_black_24dp);
                onBackPressed();
                finish();
            }else{
                databaseHelpers.addData(storeId, storeTitle, storeShortTitle, storeImg, descriptionStore, "");
                menuItem.setIcon(R.drawable.ic_favorite_black_48dp);
            }
        }else if (item_variable == R.id.share){
            String url = BaseUrlConfig.BASE_URL+storeImg;
            shareItem(url, "Hi guy, I love this news, if you interest with this news you can get more news with download my app on play store "
                    +getString(R.string.google_play_store)+getPackageName());
        }

        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        overridePendingTransition(R.anim.animation_blank, R.anim.animation_left_to_right);
    }
}
