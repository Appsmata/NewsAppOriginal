package powerrender.screen;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.design.widget.AppBarLayout;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.Toolbar;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;

import java.util.ArrayList;
import java.util.List;

import powerrender.adapter.SubCategoryAdapter;
import powerrender.modal.Callback.CallbackNewsByCategory;
import powerrender.modal.Category;
import powerrender.modal.News;
import powerrender.retrofitconfig.API;
import powerrender.retrofitconfig.CallJson;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class SubCategory extends AppCompatActivity {

    private static final String EXTRA_GET_OBJ = "key.EXTRA_GET_OBJ";

    public static void passingIntent(Activity activity, Category category){
        Intent intent = new Intent(activity, SubCategory.class);
        intent.putExtra(EXTRA_GET_OBJ, category);
        activity.startActivity(intent);
        activity.overridePendingTransition(R.anim.animation_right_to_left, R.anim.animation_blank);
    }

    private Toolbar toolbar;
    private ActionBar actionBar;
    private Category category;
    private AppBarLayout appBarLayout;
    private View view;
    private SwipeRefreshLayout swipeRefreshLayout;
    private Call<CallbackNewsByCategory> callbackNewsByCategoryCall;
    private RecyclerView recyclerView;
    private SubCategoryAdapter adapter;
    private int post_total = 0;
    private int failed_page = 0;
    GridLayoutManager gridLayoutManager;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_sub_category);
        view = findViewById(android.R.id.content);
        category = (Category) getIntent().getSerializableExtra(EXTRA_GET_OBJ);
        component();
        toolbarSet();
        requestData();
    }

    private void toolbarSet() {
        toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        actionBar = getSupportActionBar();
        actionBar.setDisplayHomeAsUpEnabled(true);
        actionBar.setHomeButtonEnabled(true);
        actionBar.setTitle("");
    }

    private void component() {
        appBarLayout = (AppBarLayout) findViewById(R.id.appbar_layout);
        swipeRefreshLayout = (SwipeRefreshLayout) findViewById(R.id.swipe_refresh_layout);
        recyclerView = (RecyclerView) findViewById(R.id.recyclerView);
        gridLayoutManager = new GridLayoutManager(this, 1);
        recyclerView.setLayoutManager(gridLayoutManager);

        adapter = new SubCategoryAdapter(this, recyclerView, new ArrayList<News>());
        recyclerView.setAdapter(adapter);

        adapter.setOnItemClickListener(new SubCategoryAdapter.OnItemClickListener() {
            @Override
            public void onItemClick(View view, News news, int position) {
                DetailNews.navigateParent(SubCategory.this, news.Menu_ID, false);
            }
        });

        swipeRefreshLayout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                if (callbackNewsByCategoryCall != null && callbackNewsByCategoryCall.isExecuted())
                    callbackNewsByCategoryCall.cancel();
                adapter.resetListData();
                requestData();
                swipeRefreshLayout.setRefreshing(false);
            }
        });
    }

    @Override
    protected void onPause() {
        super.onPause();
    }

    @Override
    protected void onResume() {
        super.onResume();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.news_search_no_action, menu);
        menu.findItem(R.id.go_search);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int item_variable = item.getItemId();
        if (item_variable == android.R.id.home){
            onBackPressed();
        }else if (item_variable == R.id.go_search){
            startActivity(new Intent(SubCategory.this, SearchNews.class));
        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        overridePendingTransition(R.anim.animation_blank, R.anim.animation_left_to_right);
    }

    private void displaydata(List<News> categories) {
        adapter.insertData(categories);
        if (categories.size() == 0);
    }

    private void requestData() {
        API api = CallJson.callJson();
        callbackNewsByCategoryCall = api.getNewsCategory(category.Category_ID);
        callbackNewsByCategoryCall.enqueue(new Callback<CallbackNewsByCategory>() {
            @Override
            public void onResponse(Call<CallbackNewsByCategory> call, Response<CallbackNewsByCategory> response) {
                CallbackNewsByCategory cnbc = response.body();
                if (cnbc != null){
                    displaydata(cnbc.data);
                }else{
                    //null showing
                }
            }

            @Override
            public void onFailure(Call<CallbackNewsByCategory> call, Throwable t) {
                if (!call.isCanceled());
                //null showing
            }
        });
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        if (callbackNewsByCategoryCall != null && callbackNewsByCategoryCall.isExecuted()) {
            callbackNewsByCategoryCall.cancel();
        }
    }

}
