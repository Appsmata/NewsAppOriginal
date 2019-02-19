package powerrender.db;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.DatabaseUtils;
import android.database.SQLException;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteException;
import android.database.sqlite.SQLiteOpenHelper;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.util.ArrayList;

import powerrender.retrofitconfig.BaseUrlConfig;

/**
 * Created by SUPRIYANTO on 05/05/2018.
 */

public class DatabaseHelpers extends SQLiteOpenHelper {

    public static SQLiteDatabase db;
    private Context context;
    String DB_PATH;

    public DatabaseHelpers(Context context) {
        super(context, Utils.DATABASE_NAME, null, Utils.DATABASE_VERSION);
        DB_PATH = BaseUrlConfig.SQL_DB_PATH;
    }

    @Override
    public void onCreate(SQLiteDatabase sqLiteDatabase) {
        sqLiteDatabase.execSQL(Utils.CREATE_TABLE_SQL);
    }

    @Override
    public void onUpgrade(SQLiteDatabase sqLiteDatabase, int i, int i1) {

        sqLiteDatabase.execSQL("DROP TABLE IF EXISTS " + Utils.TABLE_NAME);
        onCreate(sqLiteDatabase);
    }

    public void createDatabase(SQLiteDatabase sqLiteDatabase){
        boolean dbExist = checkDatabase();
        db = null;

        if (dbExist){

        }else {
            db = this.getReadableDatabase();
            db.close();
            try{
                copyDatabase();
            }catch (IOException ex){
                throw new Error("Error Copy DB");
            }
        }
    }

    private boolean checkDatabase() {
        File file = new File(DB_PATH + Utils.DATABASE_NAME);
        return file.exists();
    }

    private void copyDatabase() throws IOException{
        InputStream inputStream = context.getAssets().open(Utils.DATABASE_NAME);
        String outFileName = DB_PATH + Utils.DATABASE_NAME;
        OutputStream outputStream = new FileOutputStream(outFileName);

        byte[] bytes = new byte[1024];
        int length;
        while ((length = inputStream.read(bytes)) > 0){
            outputStream.write(bytes, 0, length);
        }

        outputStream.flush();
        outputStream.close();
        inputStream.close();
    }

    public void openDatabase(){
        db = getWritableDatabase();
        String path = DB_PATH + Utils.DATABASE_NAME;
        db = SQLiteDatabase.openDatabase(path, null, SQLiteDatabase.OPEN_READWRITE);
    }

    public ArrayList<ArrayList<Object>> getAllData(){
        ArrayList<ArrayList<Object>> dataArray = new ArrayList<>();
        db = this.getReadableDatabase();

        try{
            Cursor cursor = db.query(
                    Utils.TABLE_NAME, new String[]{Utils.ID, Utils.MENU_NAME, Utils.SHORT_TITLE, Utils.MENU_IMAGE, Utils.DESCRIPTION, Utils.DATENEWS}
                    , null, null, null, null, Utils.ID + " ASC"
            );

            if (cursor.moveToFirst()){
                do {
                    ArrayList<Object> dataList = new ArrayList<>();
                    dataList.add(cursor.getLong(0));
                    dataList.add(cursor.getString(1));
                    dataList.add(cursor.getString(2));
                    dataList.add(cursor.getString(3));
                    dataList.add(cursor.getString(4));
                    dataList.add(cursor.getString(5));

                    dataArray.add(dataList);
                }while (cursor.moveToNext());
            }

            cursor.close();
        }catch (SQLiteException ex){
            ex.printStackTrace();
        }

        return dataArray;
    }

    public ArrayList<ArrayList<Object>> getAllDataOne(int id){
        ArrayList<ArrayList<Object>> dataArray = new ArrayList<>();
        db = this.getReadableDatabase();

        try{
            Cursor cursor = db.query(
                    Utils.TABLE_NAME, new String[]{Utils.ID, Utils.MENU_NAME, Utils.MENU_IMAGE}
                    , Utils.ID+"=?"+id, null, null, null, Utils.ID + " ASC"
            );

            if (cursor.moveToFirst()){
                do {
                    ArrayList<Object> dataList = new ArrayList<>();
                    dataList.add(cursor.getLong(0));
                    dataList.add(cursor.getString(1));
                    dataList.add(cursor.getString(2));

                    dataArray.add(dataList);
                }while (cursor.moveToNext());
            }

            cursor.close();
        }catch (SQLiteException ex){
            ex.printStackTrace();
        }

        return dataArray;
    }

    public boolean isDataExist(long id){
        db = this.getReadableDatabase();
        boolean existDatabase = false;

        try{
            Cursor cursor = db.query(Utils.TABLE_NAME, new String[]
                    {Utils.ID}, Utils.ID +"="+id
            ,null, null, null, null, null);
            if (cursor.getCount() > 0){
                existDatabase = true;
            }

            cursor.close();
        }catch (SQLException ex){
            ex.printStackTrace();
        }

        return existDatabase;
    }

    public boolean isPreviousDataExist(){
        boolean exist = false;
        try{
            Cursor cursor = db.query(Utils.TABLE_NAME,
                    new String[]{Utils.ID}, null, null, null, null, null);

            if (cursor.getCount() > 0){
                exist = true;
            }
        }catch (SQLException ex){
            ex.printStackTrace();
        }

        return exist;
    }

    public void addData(long id, String name, String short_title, String image, String description, String dateNews){
        ContentValues cv = new ContentValues();

        cv.put(Utils.ID, id);
        cv.put(Utils.MENU_NAME, name);
        cv.put(Utils.SHORT_TITLE, short_title);
        cv.put(Utils.MENU_IMAGE, image);
        cv.put(Utils.DESCRIPTION, description);
        cv.put(Utils.DATENEWS, dateNews);

        try{
            db.insert(Utils.TABLE_NAME, null, cv);
        }catch (Exception ex){
            ex.printStackTrace();
        }
    }

    public void deleteData(long id){
        try {
            db.delete(Utils.TABLE_NAME, Utils.ID+"="+id, null);
        }catch (Exception ex){
            ex.printStackTrace();
        }
    }

    public void deleteAllData(){
        try {
            db.delete(Utils.TABLE_NAME, null, null);
        }catch (Exception ex){
            ex.printStackTrace();
        }
    }

    public void close(){
        db.close();
    }

    public long getUpdateCountWish(){
        db = this.getReadableDatabase();
        long count = DatabaseUtils.queryNumEntries(db, Utils.TABLE_NAME);
        db.close();
        return count;
    }
}
