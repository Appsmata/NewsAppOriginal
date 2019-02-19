package powerrender.adapter;

import android.app.Activity;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.balysv.materialripple.MaterialRippleLayout;
import com.squareup.picasso.Picasso;

import java.util.ArrayList;
import java.util.List;

import powerrender.modal.NewsSearch;
import powerrender.retrofitconfig.BaseUrlConfig;
import powerrender.screen.R;

/**
 * Created by SUPRIYANTO on 09/08/2018.
 */

public class SearchNewsAdapter extends RecyclerView.Adapter<RecyclerView.ViewHolder> {

    private Activity activity;
    private List<NewsSearch> newsSearches;
    public OnItemClickListener onItemClickListener;
    private boolean loading;
    public interface OnItemClickListener{
        void onItemClick(View view, NewsSearch newsSearch, int i);
    }
    public void setOnItemClickListener(OnItemClickListener onItemClickListener){
        this.onItemClickListener = onItemClickListener;
    }

    public SearchNewsAdapter(Activity activity, List<NewsSearch> newsSearches) {
        this.activity = activity;
        this.newsSearches = newsSearches;
    }

    public class SViewHolder extends RecyclerView.ViewHolder{

        public ImageView imgsubCategory;
        public TextView textsubCategory;
        public MaterialRippleLayout lyt_parent;

        public SViewHolder(View itemView) {
            super(itemView);

            imgsubCategory = (ImageView) itemView.findViewById(R.id.imgsubCategory);
            textsubCategory = (TextView) itemView.findViewById(R.id.textsubCategory);
            lyt_parent = (MaterialRippleLayout) itemView.findViewById(R.id.lyt_parent);
        }
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.card_sub_category, parent, false);
        return new SViewHolder(view);
    }

    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder holder, final int position) {
        final NewsSearch newsSearch = newsSearches.get(position);
        SViewHolder sViewHolder = (SViewHolder) holder;
        Picasso.with(activity).load(BaseUrlConfig.BASE_URL+newsSearch.Menu_image).into(sViewHolder.imgsubCategory);
        sViewHolder.textsubCategory.setText(newsSearch.Menu_name);
        sViewHolder.lyt_parent.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (onItemClickListener != null){
                    onItemClickListener.onItemClick(view, newsSearch, position);
                }
            }
        });
    }

    @Override
    public int getItemCount() {
        return newsSearches.size();
    }


    public void resetListData(){
        this.newsSearches = new ArrayList<>();
        notifyDataSetChanged();
    }
}

