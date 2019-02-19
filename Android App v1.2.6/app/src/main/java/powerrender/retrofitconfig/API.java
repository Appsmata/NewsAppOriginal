package powerrender.retrofitconfig;

import powerrender.modal.Callback.CallbackBackgroundDrawer;
import powerrender.modal.Callback.CallbackCategory;
import powerrender.modal.Callback.CallbackCountComment;
import powerrender.modal.Callback.CallbackNews;
import powerrender.modal.Callback.CallbackNewsByCategory;
import powerrender.modal.Callback.CallbackNewsDetail;
import powerrender.modal.Callback.CallbackNewsSearch;
import powerrender.modal.Callback.CallbackNewsSlider;
import powerrender.modal.Callback.CallbackShowComment;
import powerrender.modal.Callback.FeedbackModal;
import retrofit2.Call;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.Headers;
import retrofit2.http.POST;
import retrofit2.http.Query;

/**
 * Created by SUPRIYANTO on 02/08/2018.
 */

public interface API {
    String CACHE = "Cache-Control: max-age=0";
    String AGENT = "User-Agent: PowerRender";

    String GetNewsSearch = "api/get-news-search.php";
    String GetNewsSlider = "api/get-news-slider.php";
    String GetAllNews = "api/get-all-news.php";
    String GetAllCategory = "api/get-all-category.php";
    String GetAllNewsbyCategory = "api/get-news-by-category-id.php";
    String DetailNews = "api/get-news-detail.php";
    String Feedback = "includes/feedback.php";
    String ShowComment = "api/get-comment-by-id.php";
    String CountComment = "api/get-record-comment.php";
    String BackgroundDrawer = "api/get-bg-drawer.php";
    String AccessKeyString = "?accesskey=";
    String AccessKeyValue = "powerrender"; // change accesskey with you want, this accesskey must same with your accesskey in admin panel

    @Headers({CACHE, AGENT})
    @GET(GetNewsSearch)
    Call<CallbackNewsSearch> getNewsSearch(
            @Query("keyword") String keyword
    );

    @Headers({CACHE, AGENT})
    @GET(GetAllCategory+AccessKeyString+AccessKeyValue)
    Call<CallbackCategory> getCategory();

    @Headers({CACHE, AGENT})
    @GET(GetNewsSlider+AccessKeyString+AccessKeyValue)
    Call<CallbackNewsSlider> getNewsSlider();

    @Headers({CACHE, AGENT})
    @GET(GetAllNews+AccessKeyString+AccessKeyValue)
    Call<CallbackNews> getAllNews(
            @Query("total") int total,
            @Query("page") int page
    );

    @Headers({CACHE, AGENT})
    @GET(DetailNews+AccessKeyString+AccessKeyValue)
    Call<CallbackNewsDetail> getAllNewsDetail(
            @Query("menu_id") long Menu_ID
    );

    @Headers({CACHE, AGENT})
    @GET(GetAllNewsbyCategory+AccessKeyString+AccessKeyValue)
    Call<CallbackNewsByCategory> getNewsCategory(
            @Query("category_id") int Category_ID
    );

    @Headers({CACHE, AGENT})
    @GET(ShowComment+AccessKeyString+AccessKeyValue)
    Call<CallbackShowComment> getShowComment(
            @Query("Menu_ID") int Menu_ID
    );

    @Headers({CACHE, AGENT})
    @GET(CountComment+AccessKeyString+AccessKeyValue)
    Call<CallbackCountComment> getCountComment(
            @Query("Menu_ID") long Menu_ID
    );

    @Headers({CACHE, AGENT})
    @GET(BackgroundDrawer+AccessKeyString+AccessKeyValue)
    Call<CallbackBackgroundDrawer> getImageDrawer(

    );

    @FormUrlEncoded
    @POST(Feedback)
    Call<FeedbackModal> feedBack(
            @Field("full_name") String full_name,
            @Field("email") String email,
            @Field("phone") String phone,
            @Field("gender") String gender,
            @Field("city") String city,
            @Field("country") String country,
            @Field("txt_feed") String txt_feed

    );
}
