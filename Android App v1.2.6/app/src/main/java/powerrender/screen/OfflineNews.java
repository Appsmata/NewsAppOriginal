package powerrender.screen;

import android.content.DialogInterface;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.view.MenuItem;
import android.view.View;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.TextView;

import java.util.ArrayList;

import powerrender.adapter.FavoriteNewsAdapter;
import powerrender.db.DatabaseHelpers;

public class OfflineNews extends AppCompatActivity{

    TextView not_yet;
    ListView listviewFavorites;
    ProgressBar progressFavorites;
    DatabaseHelpers databaseHelpers;
    FavoriteNewsAdapter favoriteNewsAdapter;
    AlertDialog.Builder builder;
    ArrayList<ArrayList<Object>> getDatas = new ArrayList<>();
    public static final ArrayList<Integer> Menu_ID = new ArrayList<>();
    public static final ArrayList<Object> Menu_name = new ArrayList<>();
    public static final ArrayList<Object> Short_title = new ArrayList<>();
    public static final ArrayList<Object> Menu_image = new ArrayList<>();
    public static final ArrayList<Object> Description = new ArrayList<>();
    public static final ArrayList<Object> Date_News = new ArrayList<>();

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_favorite_news);
        not_yet = (TextView) findViewById(R.id.not_yet);
        listviewFavorites = (ListView) findViewById(R.id.listviewFavorites);
        progressFavorites = (ProgressBar) findViewById(R.id.progress_loading);
        databaseHelpers = new DatabaseHelpers(this);
        favoriteNewsAdapter = new FavoriteNewsAdapter(this);

        new getDataFromServer().execute();

        favoriteNewsAdapter.setOnItemClickListener(new FavoriteNewsAdapter.OnItemClickListener() {
            @Override
            public void onItemClick(View view, int i) {
                Intent intent = new Intent(getApplicationContext(), DetailNewsFavorite.class);
                intent.putExtra("menu_id", Menu_ID.get(i));
                intent.putExtra("menu_title", (String) Menu_name.get(i));
                intent.putExtra("short_title", (String) Short_title.get(i));
                intent.putExtra("menu_image", (String) Menu_image.get(i));
                intent.putExtra("description", (String) Description.get(i));
                startActivity(intent);
                overridePendingTransition(R.anim.animation_right_to_left, R.anim.animation_blank);
            }
        });

        toolbarSet();
    }

    private void toolbarSet() {
//        toolbar = (Toolbar) view.findViewById(R.id.toolbar);
//        ((AppCompatActivity) getActivity()).setSupportActionBar(toolbar);
//        actionBar = ((AppCompatActivity) getActivity()).getSupportActionBar();
//        actionBar.setDisplayHomeAsUpEnabled(true);
//        actionBar.setHomeButtonEnabled(true);
//        actionBar.setTitle("");
    }

//    private void showPopUpDelete(final int id) {
//
//        builder = new AlertDialog.Builder(this);
//        builder.setTitle("Hapus atau Lihat");
//        builder.setMessage("Klik hapus jika ingin menghapus, klik lihat jika ingin melihat");
//        builder.setPositiveButton(R.string.deleted, new DialogInterface.OnClickListener() {
//            @Override
//            public void onClick(DialogInterface dialogInterface, int i) {
//                databaseHelpers.deleteData(id);
//                listviewFavorites.invalidateViews();
//                new getDataFromServer().execute();
//            }
//        });
//        builder.setNegativeButton(R.string.view, new DialogInterface.OnClickListener() {
//            @Override
//            public void onClick(DialogInterface dialogInterface, int i) {
//
//            }
//        });
//        builder.create();
//        builder.show();
//    }

    public void clearData(){
        Menu_ID.clear();
        Menu_name.clear();
        Short_title.clear();
        Menu_image.clear();
        Description.clear();
        Date_News.clear();
    }

    private class getDataFromServer extends AsyncTask<Void, Void, Void> {

        getDataFromServer(){
            if (!progressFavorites.isShown()){
                progressFavorites.setVisibility(View.GONE);
            }
        }

        @Override
        protected Void doInBackground(Void... voids) {
            getDataFromSql();
            return null;
        }

        @Override
        protected void onPostExecute(Void aVoid) {
            if (Menu_ID.size() > 0){
                listviewFavorites.setAdapter(favoriteNewsAdapter);
            }else {
                not_yet.setVisibility(View.VISIBLE);
            }
        }
    }

    private void getDataFromSql() {

        clearData();
        getDatas = databaseHelpers.getAllData();
        for (int o = 0; o<getDatas.size(); o++){
            ArrayList<Object> rows = getDatas.get(o);
            Menu_ID.add(Integer.parseInt(rows.get(0).toString()));
            Menu_name.add(rows.get(1).toString());
            Short_title.add(rows.get(2).toString());
            Menu_image.add(rows.get(3).toString());
            Description.add(rows.get(4).toString());
            Date_News.add(rows.get(5).toString());
        }
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int item_variable = item.getItemId();
        if (item_variable == android.R.id.home){

        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onResume() {
        super.onResume();
        new getDataFromServer().execute();
        favoriteNewsAdapter.notifyDataSetChanged();
    }

    private void showPopUp() {
        builder = new AlertDialog.Builder(this);
        builder.setTitle(getString(R.string.delete_all));
        builder.setMessage(getString(R.string.message_delete_all));
        builder.setPositiveButton(R.string.deleted, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                databaseHelpers.deleteAllData();
                listviewFavorites.invalidateViews();
                new getDataFromServer().execute();
            }
        });
        builder.create();
        builder.show();
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        Intent intent = new Intent(OfflineNews.this, MainActivity.class);
        startActivity(intent);
        overridePendingTransition(R.anim.animation_left_to_right, R.anim.animation_blank);
    }
}
