package powerrender.fragment;

import android.os.Bundle;
import android.os.Handler;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.content.ContextCompat;
import android.support.v4.view.ViewPager;
import android.support.v7.widget.CardView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import java.util.ArrayList;
import java.util.List;

import powerrender.adapter.NewsSliderAdapter;
import powerrender.modal.Callback.CallbackNews;
import powerrender.modal.Callback.CallbackNewsSlider;
import powerrender.modal.NewsSlider;
import powerrender.retrofitconfig.API;
import powerrender.retrofitconfig.CallJson;
import powerrender.screen.DetailNews;
import powerrender.screen.R;
import powerrender.setting.ToolsUtils;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

/**
 * Created by SUPRIYANTO on 02/08/2018.
 */

public class NewsSliderFragment extends Fragment{

    private View root_view;
    private View root_recycler;
    private Call<CallbackNews> callbackNewsCall;
    private ViewPager viewPager;
    private LinearLayout layout_dots;
    private ImageButton btn_next, btn_prev;
    private Handler handler = new Handler();
    private Runnable runnable = null;
    private NewsSliderAdapter newsSliderAdapter;
    private TextView title, date_news;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        root_view = inflater.inflate(R.layout.fragment_slider, null);
        component();
        requestData();
        return root_view;
    }

    private void component() {
        root_recycler = (CardView) root_view.findViewById(R.id.lyt_cart);
        viewPager = (ViewPager) root_view.findViewById(R.id.pager);
        layout_dots = (LinearLayout) root_view.findViewById(R.id.layout_dots);
        btn_next = (ImageButton) root_view.findViewById(R.id.bt_next);
        btn_prev = (ImageButton) root_view.findViewById(R.id.bt_previous);
        title = (TextView) root_view.findViewById(R.id.featured_news_title);
        date_news = (TextView) root_view.findViewById(R.id.date_news);
        newsSliderAdapter = new NewsSliderAdapter(getActivity(), new ArrayList<NewsSlider>());

        btn_next.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                next();
            }
        });

        btn_prev.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                prev();
            }
        });
    }

    private void displayFromServer(List<NewsSlider> newsSliders){
        newsSliderAdapter.setItem(newsSliders);
        viewPager.setAdapter(newsSliderAdapter);
        final ViewGroup.LayoutParams layoutParams = viewPager.getLayoutParams();
        layoutParams.height = ToolsUtils.getFeaturedNewsImageHeight(getActivity());
        viewPager.setLayoutParams(layoutParams);

        viewPager.setCurrentItem(0);
        title.setText(newsSliderAdapter.getItem(0).Menu_name);
        date_news.setText(newsSliderAdapter.getItem(0).Date_News);
        dotsAdd(layout_dots, newsSliderAdapter.getCount(), 0);
        viewPager.addOnPageChangeListener(new ViewPager.OnPageChangeListener() {
            @Override
            public void onPageScrolled(int position, float positionOffset, int positionOffsetPixels) {

            }

            @Override
            public void onPageSelected(int position) {
                NewsSlider change_news = newsSliderAdapter.getItem(position);
                title.setText(change_news.Menu_name);
                date_news.setText(change_news.Date_News);
                dotsAdd(layout_dots, newsSliderAdapter.getCount(), position);
            }

            @Override
            public void onPageScrollStateChanged(int state) {

            }
        });
        autoSlider(newsSliderAdapter.getCount());
        newsSliderAdapter.setOnItemClickListener(new NewsSliderAdapter.OnItemClickListener() {
            @Override
            public void onItemClick(View view, NewsSlider news) {
                DetailNews.navigateParent(getActivity(), news.Menu_ID, false);
                Log.d("You click", news.Menu_ID.toString());
            }
        });
        root_recycler.setVisibility(View.VISIBLE);

    }

    private void requestData() {
        API api = CallJson.callJson();
        api.getNewsSlider().enqueue(new Callback<CallbackNewsSlider>() {
            @Override
            public void onResponse(Call<CallbackNewsSlider> call, Response<CallbackNewsSlider> response) {
                CallbackNewsSlider callbackNews = response.body();
                if (callbackNews != null){
                    displayFromServer(callbackNews.data);
                }
            }

            @Override
            public void onFailure(Call<CallbackNewsSlider> call, Throwable t) {

            }
        });
    }

    public void autoSlider(final int slider){
        runnable = new Runnable() {
            @Override
            public void run() {
                int position = viewPager.getCurrentItem();
                position = position + 1;
                if (position >= slider) position = 0;
                viewPager.setCurrentItem(position);
                handler.postDelayed(runnable, 3000);
            }
        };
        handler.postDelayed(runnable, 3000);
    }

    private void next(){
        int position = viewPager.getCurrentItem();
        position = position + 1;
        if (position >= newsSliderAdapter.getCount()) position = 0;
        viewPager.setCurrentItem(position);
    }

    private void prev(){
        int position = viewPager.getCurrentItem();
        position = position - 1;
        if (position < 0) position = newsSliderAdapter.getCount();
        viewPager.setCurrentItem(position);
    }

    @Override
    public void onDestroy() {
        if (runnable != null) handler.removeCallbacks(runnable);
        super.onDestroy();
    }

    private void dotsAdd(LinearLayout layout_dots, int size, int current){
        ImageView[] dots_img = new ImageView[size];
        layout_dots.removeAllViews();
        for (int a = 0; a < dots_img.length; a++){
            dots_img[a] = new ImageView(getActivity());
            int w_h_dots = 16;
            LinearLayout.LayoutParams layoutParams = new LinearLayout.LayoutParams(new ViewGroup.LayoutParams(w_h_dots, w_h_dots));
            layoutParams.setMargins(9,9,9,9);
            dots_img[a].setLayoutParams(layoutParams);
            dots_img[a].setImageResource(R.drawable.dots_shape);
            dots_img[a].setColorFilter(ContextCompat.getColor(getActivity(), R.color.black_color));
            layout_dots.addView(dots_img[a]);
        }
        if (dots_img.length > 1){
            dots_img[current].setColorFilter(ContextCompat.getColor(getActivity(), R.color.colorAccent));
        }
    }
}
