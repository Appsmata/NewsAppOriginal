package powerrender.screen;

import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.support.annotation.Nullable;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Toast;

import powerrender.modal.Callback.FeedbackModal;
import powerrender.retrofitconfig.API;
import powerrender.retrofitconfig.CallJson;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

/**
 * Created by SUPRIYANTO on 15/09/2018.
 */

public class Feedback extends AppCompatActivity{

    private RadioGroup radioGroup;
    private RadioButton radioButton;
    private EditText name, email, phone, city, country, txt_feed;
    private String nameString, emailString, phoneString, cityString, countryString, txtfeedString;
    private ProgressDialog progressDialog;
    private String email_shared, name_shared;
    private Toolbar toolbar;
    private ActionBar actionBar;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_feedback);
        initToolbar();

        radioGroup = (RadioGroup) findViewById(R.id.gender_group);
        radioButton = (RadioButton) findViewById(R.id.female);
        radioButton = (RadioButton) findViewById(R.id.male);
        name = (EditText) findViewById(R.id.input_full_name);
        email = (EditText) findViewById(R.id.input_email);
        phone = (EditText) findViewById(R.id.input_phone);
        city = (EditText) findViewById(R.id.input_city);
        country = (EditText) findViewById(R.id.input_country);
        txt_feed = (EditText) findViewById(R.id.input_feedback);

        SharedPreferences preferences = PreferenceManager.getDefaultSharedPreferences(getApplicationContext());
        email_shared = preferences.getString("login_email", "");
        name_shared = preferences.getString("login_name", "");
        name.setText(name_shared);
        email.setText(email_shared);

        ((Button) findViewById(R.id.do_submit)).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (isValidForm()){
                    try {
                        postData();
                    }catch (Exception e){
                        Toast.makeText(getApplicationContext(), "Gender Must be Filled", Toast.LENGTH_LONG).show();
                        progressDialog.dismiss();
                    }
                }
            }
        });
        progressDialog = new ProgressDialog(this);
        progressDialog.setTitle(getString(R.string.please_wait_loading_data));
        progressDialog.setCancelable(false);
    }

    private void initToolbar(){
        toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        actionBar = getSupportActionBar();
        actionBar.setDisplayHomeAsUpEnabled(true);
        actionBar.setHomeButtonEnabled(true);
        actionBar.setTitle("Feedback");
    }

    private boolean isValidForm(){
        if (name.getText().toString().equals("")){
            Toast.makeText(getApplicationContext(), "Name Must be Filled", Toast.LENGTH_LONG).show();
            return false;
        }
        if (email.getText().toString().equals("")){
            Toast.makeText(getApplicationContext(), "Email Must be Filled", Toast.LENGTH_LONG).show();
            return false;
        }
        if (phone.getText().toString().equals("")){
            Toast.makeText(getApplicationContext(), "Phone Must be Filled", Toast.LENGTH_LONG).show();
            return false;
        }
        if (city.getText().toString().equals("")){
            Toast.makeText(getApplicationContext(), "City Must be Filled", Toast.LENGTH_LONG).show();
            return false;
        }
        if (country.getText().toString().equals("")){
            Toast.makeText(getApplicationContext(), "Country Must be Filled", Toast.LENGTH_LONG).show();
            return false;
        }
        if (txt_feed.getText().toString().equals("")){
            Toast.makeText(getApplicationContext(), "Text Field Must be Filled", Toast.LENGTH_LONG).show();
            return false;
        }
        return true;
    }

    private void postData(){
        progressDialog.show();

        int genderSet = radioGroup.getCheckedRadioButtonId();
        radioButton = (RadioButton) findViewById(genderSet);

        final String gender = radioButton.getText().toString();
        nameString = name.getText().toString();
        emailString = email.getText().toString();
        phoneString = phone.getText().toString();
        cityString = city.getText().toString();
        countryString = country.getText().toString();
        txtfeedString = txt_feed.getText().toString();

        API api = CallJson.callJson();
        Call<FeedbackModal> call = api.feedBack(nameString,emailString, phoneString, gender, cityString, countryString, txtfeedString);
        call.enqueue(new Callback<FeedbackModal>() {
            @Override
            public void onResponse(Call<FeedbackModal> call, Response<FeedbackModal> response) {
                String value = response.body().getValue();
                String message = response.body().getMessage();
                progressDialog.dismiss();
                if (value.equals("1")) {
                    showSuccessPopup();
                } else {
                    Toast.makeText(getApplicationContext(), message, Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<FeedbackModal> call, Throwable t) {
                progressDialog.dismiss();
                Toast.makeText(getApplicationContext(), getString(R.string.check_connection), Toast.LENGTH_SHORT).show();
            }

        });
    }

    private void showSuccessPopup() {
        AlertDialog.Builder builder =
                new AlertDialog.Builder(this);
        builder.setTitle(R.string.success);
        builder.setMessage(R.string.thank_feedback);
        builder.setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialogInterface, int i) {
                Intent intent = new Intent(getApplicationContext(), MainActivity.class);
                startActivity(intent);
                overridePendingTransition(R.anim.animation_right_to_left, R.anim.animation_blank);
            }
        });
        builder.show();

    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        if (id == android.R.id.home){
            onBackPressed();
        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        Intent intent = new Intent(Feedback.this, MainActivity.class);
        startActivity(intent);
        overridePendingTransition(R.anim.animation_right_to_left, R.anim.animation_blank);
    }
}
