package powerrender.fragment;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import java.util.ArrayList;

import powerrender.screen.R;
import powerrender.adapter.CategoryNewsFragmentAdapter;
import powerrender.modal.Callback.CallbackCategory;
import powerrender.modal.Category;
import powerrender.retrofitconfig.API;
import powerrender.retrofitconfig.CallJson;
import powerrender.screen.SubCategory;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

/**
 * Created by SUPRIYANTO on 02/08/2018.
 */

public class CategoryNewsFragment extends Fragment {
    private View root_view;
    private RecyclerView recyclerView;
    private CategoryNewsFragmentAdapter adapter;
    private GridLayoutManager gridLayoutManager;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        root_view = inflater.inflate(R.layout.fragment_category, null);
        component();
        requestData();
        return root_view;
    }

    private void component() {
        recyclerView = (RecyclerView) root_view.findViewById(R.id.recyclerViewFragment);
        gridLayoutManager = new GridLayoutManager(getActivity(), 2);
        recyclerView.setLayoutManager(gridLayoutManager);
        adapter = new CategoryNewsFragmentAdapter(getActivity(), new ArrayList<Category>());
        adapter.setOnItemClickListener(new CategoryNewsFragmentAdapter.OnItemClickListener() {
            @Override
            public void onItemClick(View view, Category category) {
                SubCategory.passingIntent(getActivity(), category);
            }
        });
        recyclerView.setAdapter(adapter);
    }

    private void requestData() {
        final API api = CallJson.callJson();
        api.getCategory().enqueue(new Callback<CallbackCategory>() {
            @Override
            public void onResponse(Call<CallbackCategory> call, Response<CallbackCategory> response) {
                CallbackCategory cl = response.body();
                if (cl != null)
                recyclerView.setVisibility(View.VISIBLE);
                adapter.setItems(cl.data);
            }

            @Override
            public void onFailure(Call<CallbackCategory> call, Throwable t) {

            }
        });
    }
}
