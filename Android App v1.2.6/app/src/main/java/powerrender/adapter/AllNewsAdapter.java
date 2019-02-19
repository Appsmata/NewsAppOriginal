package powerrender.adapter;

import android.app.Activity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;

import com.balysv.materialripple.MaterialRippleLayout;
import com.squareup.picasso.Picasso;

import java.util.ArrayList;
import java.util.List;

import powerrender.modal.News;
import powerrender.retrofitconfig.BaseUrlConfig;
import powerrender.screen.R;

/**
 * Created by SUPRIYANTO on 08/08/2018.
 */

public class AllNewsAdapter extends RecyclerView.Adapter<RecyclerView.ViewHolder> {

    private Activity activity;
    private List<News> newsList;
    public OnItemClickListener onItemClickListener;
    public OnLoadMoreListener onLoadMoreListener;
    public boolean loading;
    private int VIEW_PROGRES = 0;
    private int VIEW_ITEM = 1;

    public interface OnItemClickListener{
        void onItemClick(View view, News news);
    }

    public AllNewsAdapter(Activity activity, List<News> newsList){
        this.activity = activity;
        this.newsList = newsList;
    }

    public class SourceView extends RecyclerView.ViewHolder{

        public MaterialRippleLayout lyt_parent;
        public ImageView imgsubCategory;
        public TextView title;
        public TextView shortTitle;
        public TextView date_news;

        public SourceView(View itemView) {
            super(itemView);
            lyt_parent = (MaterialRippleLayout) itemView.findViewById(R.id.material_ripple);
            imgsubCategory = (ImageView) itemView.findViewById(R.id.imgBig);
            title = (TextView) itemView.findViewById(R.id.title);
            shortTitle = (TextView) itemView.findViewById(R.id.shortTitle);
            date_news = (TextView) itemView.findViewById(R.id.date_news);
        }
    }

    public static class ProgressViewHolder extends RecyclerView.ViewHolder{

        public ProgressBar progressBar;

        public ProgressViewHolder(View itemView) {
            super(itemView);
            progressBar = (ProgressBar) itemView.findViewById(R.id.progress_loading);
        }
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        RecyclerView.ViewHolder viewHolder;
        if (viewType == VIEW_ITEM){
            View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.card_all_news, parent, false);
            viewHolder = new SourceView(view);
        }else{
            View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.loading_data, parent, false);
            viewHolder = new SourceView(view);
        }
        return viewHolder;
    }

    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder holder, final int position) {
        if (holder instanceof SourceView){
            final News news = newsList.get(position);
            SourceView sourceView = (SourceView) holder;
            Picasso.with(activity).load(BaseUrlConfig.BASE_URL+news.Menu_image).into(sourceView.imgsubCategory);
            sourceView.title.setText(news.Menu_name);
            sourceView.shortTitle.setText(news.Short_title);
            sourceView.date_news.setText(news.Date_News);
            sourceView.lyt_parent.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if (onItemClickListener != null){
                        onItemClickListener.onItemClick(view, news);
                    }
                }
            });
        }else {
            ((ProgressViewHolder) holder).progressBar.setIndeterminate(true);
        }
    }

    @Override
    public int getItemCount() {
        return newsList.size();
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    @Override
    public int getItemViewType(int position) {
        return this.newsList.get(position) != null ? VIEW_ITEM : VIEW_PROGRES;
    }

    public void resetData(){
        this.newsList = new ArrayList<>();
        notifyDataSetChanged();
    }

    public void insertData(List<News> newsList){
//        setLoaded();
        int itemStart = getItemCount();
        int itemCount = newsList.size();
        this.newsList.addAll(newsList);
        notifyItemRangeInserted(itemStart, itemCount);
    }

    public void setLoaded(){
        loading = false;
        for (int k = 0; k<getItemCount(); k++){
            if (newsList.get(k) == null){
                newsList.remove(k);
                notifyItemRemoved(k);
            }
        }
    }

    public void setLoading(){
        if (getItemCount() != 0){
            this.newsList.add(null);
            notifyItemInserted(getItemCount() - 1);
            loading = true;
        }
    }

    public void setOnItemClickListener(OnItemClickListener onItemClickListener){
        this.onItemClickListener = onItemClickListener;
    }

    public void setOnLoadMoreListener(OnLoadMoreListener onLoadMoreListener){
        this.onLoadMoreListener = onLoadMoreListener;
    }

    private void lastNewsView(RecyclerView recyclerView){
        if (recyclerView.getLayoutManager() instanceof LinearLayoutManager){
            final LinearLayoutManager layoutManager = (LinearLayoutManager) recyclerView.getLayoutManager();
            recyclerView.addOnScrollListener(new RecyclerView.OnScrollListener() {
                @Override
                public void onScrolled(RecyclerView recyclerView, int dx, int dy) {
                    super.onScrolled(recyclerView, dx, dy);
                    int lastNews = layoutManager.findLastVisibleItemPosition();
                    if (!loading && lastNews == getItemCount() - 1 && onLoadMoreListener != null){
                        if (onLoadMoreListener != null){
                            int get = getItemCount() / BaseUrlConfig.Request_Load_More;
                            onLoadMoreListener.onLoadMore(get);
                        }
                        loading = true;
                    }
                }
            });
        }
    }

    public interface OnLoadMoreListener{
        void onLoadMore(int page);
    }
}
