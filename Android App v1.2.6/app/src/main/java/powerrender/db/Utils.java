package powerrender.db;

/**
 * Created by SUPRIYANTO on 05/05/2018.
 */

public class Utils {

    //DATABASE NAME
    public static final String DATABASE_NAME = "power_render";
    public static final int DATABASE_VERSION = 3;

    //COLUMN DATABASE
    public static final String TABLE_NAME = "pr_tbl";
    public static final String ID = "id";
    public static final String MENU_NAME = "Menu_name";
    public static final String SHORT_TITLE = "Short_title";
    public static final String CATEGORY_ID = "Category";
    public static final String MENU_IMAGE = "Menu_image";
    public static final String DESCRIPTION = "Description";
    public static final String DATENEWS = "Date_News";

    //CREATE DB SQL
    public static final String CREATE_TABLE_SQL = "CREATE TABLE " + TABLE_NAME + " ("
            + ID + " INTEGER PRIMARY KEY, "
            + MENU_NAME + " TEXT, "
            + SHORT_TITLE + " TEXT, "
            + MENU_IMAGE + " TEXT, "
            + DESCRIPTION + " TEXT ,"
            + DATENEWS + " TEXT);";
}
