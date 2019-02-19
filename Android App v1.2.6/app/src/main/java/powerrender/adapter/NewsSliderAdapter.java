package powerrender.adapter;

import android.app.Activity;
import android.content.Context;
import android.support.v4.view.PagerAdapter;
import android.support.v4.view.ViewPager;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RelativeLayout;

import com.balysv.materialripple.MaterialRippleLayout;
import com.bumptech.glide.Glide;

import java.util.List;

import powerrender.modal.NewsSlider;
import powerrender.retrofitconfig.BaseUrlConfig;
import powerrender.screen.R;

/**
 * Created by SUPRIYANTO on 02/08/2018.
 */

public class NewsSliderAdapter extends PagerAdapter{

    private Activity activity;
    private List<NewsSlider> newsList;
    private OnItemClickListener onItemClickListener;
    public interface OnItemClickListener{
        void onItemClick(View view, NewsSlider news);
    }

    public void setOnItemClickListener(OnItemClickListener onItemClickListener){
        this.onItemClickListener = onItemClickListener;
    }

    public NewsSliderAdapter(Activity activity, List<NewsSlider> newsList) {
        this.activity = activity;
        this.newsList = newsList;
    }

    @Override
    public int getCount() {
        return newsList.size();
    }

    public NewsSlider getItem(int position){
        return newsList.get(position);
    }

    public void setItem(List<NewsSlider> news){
        this.newsList = news;
        notifyDataSetChanged();
    }

    @Override
    public boolean isViewFromObject(View view, Object object) {
        return view == ((RelativeLayout) object);
    }

    @Override
    public Object instantiateItem(ViewGroup container, int position) {
        final NewsSlider news = newsList.get(position);
        LayoutInflater inflater = (LayoutInflater) activity.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        View view = inflater.inflate(R.layout.fragment_item_image, container, false);
        ImageView img = (ImageView) view.findViewById(R.id.image);
        MaterialRippleLayout mtr = (MaterialRippleLayout) view.findViewById(R.id.lyt_parent);
        Glide.with(activity).load(BaseUrlConfig.BASE_URL+news.Menu_image).into(img);
        mtr.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (onItemClickListener != null){
                    onItemClickListener.onItemClick(view, news);
                }
            }
        });
        ((ViewPager) container).addView(view);
        return view;
    }

    @Override
    public void destroyItem(ViewGroup container, int position, Object object) {
        ((ViewPager) container).removeView((RelativeLayout) object);
    }
}
