package powerrender.adapter;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.balysv.materialripple.MaterialRippleLayout;
import com.bumptech.glide.Glide;

import java.util.ArrayList;
import java.util.List;

import powerrender.screen.R;
import powerrender.modal.Category;
import powerrender.retrofitconfig.BaseUrlConfig;

/**
 * Created by SUPRIYANTO on 02/08/2018.
 */

public class CategoryNewsFragmentAdapter extends RecyclerView.Adapter<RecyclerView.ViewHolder> {

    private Context context;
    private List<Category> categories = new ArrayList<>();
    private ArrayList<String> imgPath;
    private OnItemClickListener onItemClickListener;
    public interface OnItemClickListener{
        void onItemClick(View view, Category category);
    }

    public void setOnItemClickListener(OnItemClickListener onItemClickListener){
        this.onItemClickListener = onItemClickListener;
    }

    public CategoryNewsFragmentAdapter(Context context, List<Category> categories){
        this.context = context;
        this.categories = categories;
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        RecyclerView.ViewHolder viewHolder;
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.card_category, parent, false);
        viewHolder = new ViewHolder(view);
        return viewHolder;
    }

    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder holder, int position) {
        if (holder instanceof  ViewHolder){
            ViewHolder viewHolder = (ViewHolder) holder;
            final Category category = categories.get(position);
            Glide.with(context).load(BaseUrlConfig.BASE_URL+category.Category_image).into(viewHolder.imgBig);
            viewHolder.textCategory.setText(category.Category_name);
            viewHolder.material_ripple.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if (onItemClickListener != null){
                        onItemClickListener.onItemClick(view, category);
                    }
                }
            });
        }
    }

    @Override
    public int getItemCount() {
        return categories.size();
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    public void setItems(List<Category> items){
        this.categories = items;
        notifyDataSetChanged();
    }

    public class ViewHolder extends RecyclerView.ViewHolder{

        public ImageView imgBig;
        public TextView textCategory;
        public MaterialRippleLayout material_ripple;

        public ViewHolder(View itemView) {
            super(itemView);
            imgBig = (ImageView) itemView.findViewById(R.id.imgBig);
            textCategory = (TextView) itemView.findViewById(R.id.textCategory);
            material_ripple = (MaterialRippleLayout) itemView.findViewById(R.id.material_ripple);
        }
    }
}
