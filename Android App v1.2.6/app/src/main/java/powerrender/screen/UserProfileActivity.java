package powerrender.screen;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.provider.MediaStore;
import android.support.annotation.Nullable;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.util.Base64;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.squareup.picasso.MemoryPolicy;
import com.squareup.picasso.NetworkPolicy;
import com.squareup.picasso.Picasso;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.util.HashMap;
import java.util.Map;

import de.hdodenhof.circleimageview.CircleImageView;
import powerrender.retrofitconfig.BaseUrlConfig;
import powerrender.screen.notification.MySingleton;

/**
 * Created by SUPRIYANTO on 10/10/2018.
 */

public class UserProfileActivity extends AppCompatActivity {

    private CircleImageView user_image;
    private RelativeLayout edit_profile;
    private Button choose_photo, edit_process;
    private EditText user_name;
    private TextView user_registration, user_email;
    private String name, email, user_Registration;
    private ProgressDialog progressDialog;
    private int id_userse;
    private String id_users;
    private Bitmap bitmap;
    private SharedPreferences preferences;
    private SharedPreferences.Editor editor;
    private AlertDialog.Builder builder;
    private AlertDialog dialog;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_profile);
        initInterface();
    }

    private void initInterface() {
        edit_profile = (RelativeLayout) findViewById(R.id.edit_profile);
        user_image = (CircleImageView) findViewById(R.id.user_image);
        choose_photo = (Button) findViewById(R.id.choose_photo);
        edit_process = (Button) findViewById(R.id.edit_process);
        user_name = (EditText) findViewById(R.id.user_name);
        user_email = (TextView) findViewById(R.id.user_email);
        user_registration = (TextView) findViewById(R.id.user_registration);

        preferences = PreferenceManager.getDefaultSharedPreferences(getApplicationContext());
        id_userse = preferences.getInt("login_user_id", 0);
        id_users = preferences.getString("login_profile_image", "");
        name = preferences.getString("login_name", "");
        email = preferences.getString("login_email", "");
        user_Registration = preferences.getString("login_created_at", "");

        Picasso.with(getApplicationContext()).load(BaseUrlConfig.BASE_URL+BaseUrlConfig.BASE_URL_IMAGE+id_users).memoryPolicy(MemoryPolicy.NO_CACHE).networkPolicy(NetworkPolicy.NO_CACHE).into(user_image);
        Picasso.with(getApplicationContext()).invalidate(BaseUrlConfig.BASE_URL+BaseUrlConfig.BASE_URL_IMAGE+id_users);
        user_name.setText(name);
        user_email.setText(email);
        user_registration.setText(user_Registration);

        choose_photo.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                choosePhoto();
            }
        });

        edit_process.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                final int update_id = id_userse;
                final String update_name = user_name.getText().toString().trim();

                if (!update_name.isEmpty()){
                    editProfile(String.valueOf(update_id), update_name);
                    preferences = PreferenceManager.getDefaultSharedPreferences(getApplicationContext());
                    editor = preferences.edit();

                    editor.putString("login_name", user_name.getText().toString());
                    editor.apply();
                }else{
                    Toast.makeText(getApplicationContext(), getString(R.string.validate_input), Toast.LENGTH_LONG).show();
                }

            }
        });

        progressDialog = new ProgressDialog(this);
        progressDialog.setCancelable(false);
    }

    private void editProfile(final String update_id, final String update_name) {

        progressDialog.setMessage("Update your profile. . .");
        dialogShow();

        StringRequest request = new StringRequest(Request.Method.POST, BaseUrlConfig.BASE_URL + BaseUrlConfig.UPDATE, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                hideDialog();
                try {
                    JSONObject jsonObject = new JSONObject(response);
                    String success = jsonObject.getString("success");

                    if (success.equals("powerrender")){
                        Snackbar.make(edit_profile, R.string.success_upload_image, Snackbar.LENGTH_SHORT).show();
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                    hideDialog();
                }

            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                hideDialog();
                Toast.makeText(getApplicationContext(), "Error", Toast.LENGTH_LONG).show();
            }
        }){
            @Override
            protected Map<String, String> getParams(){

                Map<String, String> param = new HashMap<>();
                param.put("User_Id", update_id);
                param.put("name", update_name);
                return param;
            }
        };

        MySingleton.getmInstance(this).addToRequest(request);

    }

    private void dialogShow() {
        if (!progressDialog.isShowing()){
            progressDialog.show();
        }
    }

    private void hideDialog(){
        if (progressDialog.isShowing()){
            progressDialog.dismiss();
        }
    }

    private void choosePhoto(){
        Intent intent = new Intent();
        intent.setType("image/*");
        intent.setAction(Intent.ACTION_GET_CONTENT);
        startActivityForResult(Intent.createChooser(intent, "Select Photo"), 1);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        if (requestCode == 1 && resultCode == RESULT_OK && data != null & data.getData() != null){
            Uri photoPath = data.getData();
            try {
                bitmap = MediaStore.Images.Media.getBitmap(getContentResolver(), photoPath);
                user_image.setImageBitmap(bitmap);
            } catch (IOException e) {
                e.printStackTrace();
            }

            uploadPhoto(String.valueOf(id_userse), getBitmap(bitmap));

        }
    }

    private void uploadPhoto(final String user_id, final String user_photo) {
        final ProgressDialog progressDialog = new ProgressDialog(this);
        progressDialog.setMessage(getResources().getString(R.string.loading_upload_image));
        progressDialog.show();

        StringRequest request = new StringRequest(Request.Method.POST, BaseUrlConfig.BASE_URL + BaseUrlConfig.UPDATE_IMAGE_USERS, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                progressDialog.dismiss();
                try{
                    JSONObject jsonObject = new JSONObject(response);
                    String success = jsonObject.getString("success");
                    if (success.equals("powerrender")){

                        Snackbar.make(edit_profile, R.string.success_upload_image, Snackbar.LENGTH_SHORT).show();
                        Picasso.with(getApplicationContext()).load(BaseUrlConfig.BASE_URL+BaseUrlConfig.BASE_URL_IMAGE+id_users).memoryPolicy(MemoryPolicy.NO_CACHE).networkPolicy(NetworkPolicy.NO_CACHE).into(user_image);
                        Picasso.with(getApplicationContext()).invalidate(BaseUrlConfig.BASE_URL+BaseUrlConfig.BASE_URL_IMAGE+id_users);

                    }

                }catch (JSONException e){
                    e.printStackTrace();
                    progressDialog.dismiss();
                    Toast.makeText(getApplicationContext(), R.string.failed_upload_image, Toast.LENGTH_LONG).show();
                }
            }

        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                progressDialog.dismiss();
                Toast.makeText(getApplicationContext(), R.string.upload_image_error, Toast.LENGTH_LONG).show();
            }
        }){
            @Override
            protected Map<String, String> getParams(){
                Map<String, String> param = new HashMap<>();
                param.put("User_Id", user_id);
                param.put("profile_image", user_photo);
                return param;
            }
        };

        MySingleton.getmInstance(this).addToRequest(request);
    }

    private String getBitmap(Bitmap bitmap){

        ByteArrayOutputStream outputStream = new ByteArrayOutputStream();
        bitmap.compress(Bitmap.CompressFormat.JPEG, 100, outputStream);

        byte[] img = outputStream.toByteArray();
        String encoding = Base64.encodeToString(img, Base64.DEFAULT);
        return encoding;
    }

    @Override
    protected void onResume() {
        super.onResume();
        if (preferences.getInt("login_user_id", 0) != 0){
            Picasso.with(getApplicationContext()).load(BaseUrlConfig.BASE_URL+BaseUrlConfig.BASE_URL_IMAGE+id_users).memoryPolicy(MemoryPolicy.NO_CACHE).networkPolicy(NetworkPolicy.NO_CACHE).into(user_image);
            Picasso.with(getApplicationContext()).invalidate(BaseUrlConfig.BASE_URL+BaseUrlConfig.BASE_URL_IMAGE+id_users);
        }
    }

    public void goAbout(View view){
        builder = new AlertDialog.Builder(this);
        view = LayoutInflater.from(UserProfileActivity.this).inflate(R.layout.card_about_app, null);
        builder.setView(view);
        dialog = builder.create();
        dialog.show();
    }

    public void goRating(View view){
        builder = new AlertDialog.Builder(this);
        view = LayoutInflater.from(UserProfileActivity.this).inflate(R.layout.card_rating, null);
        Button ratingMe = (Button) view.findViewById(R.id.ratingMe);
        builder.setView(view);
        dialog = builder.create();
        dialog.show();

        ratingMe.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                startActivity(new Intent(Intent.ACTION_VIEW, Uri.parse(BaseUrlConfig.YOUR_GOOGLE_PLAY_STORE_URL+getPackageName())));
            }
        });

    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        Intent intent = new Intent(UserProfileActivity.this, MainActivity.class);
        startActivity(intent);
        overridePendingTransition(R.anim.animation_right_to_left, R.anim.animation_blank);
    }
}
