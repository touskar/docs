package test.sample.paytech.paytech_sdk_test;

import androidx.appcompat.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Toast;
import java.util.HashMap;

import sdk.paytech.sn.PCallback;
import sdk.paytech.sn.PayTech;

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        Button button = (Button) this.findViewById(R.id.buy);
        RadioGroup buttonGroup = (RadioGroup) this.findViewById(R.id.radioGroup);
        final RadioButton radioButtonFull = (RadioButton) this.findViewById(R.id.radioButton_full_screen);
        final RadioButton radioButtonFloat = (RadioButton) this.findViewById(R.id.radioButton_floating);
        radioButtonFloat.setChecked(true);

        button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Log.i("click", "click on button");
                HashMap<String,Object> params = new HashMap<>();
                params.put("item_id", 2);

                new PayTech(MainActivity.this)
                        .setRequestTokenUrl("https://sample.paytech.sn/paiement.php")
                        .setParams(params)
                        .setPresentationMode(radioButtonFull.isChecked() ? PayTech.FULL_SCREEN : PayTech.FLOATING_VIEW) // optional default to FULL_SCREEN
                        .setFloatTopMargin(25)
                        .setLoadingDialogText(MainActivity.this.getString(R.string.loading)) //optional Chargement
                        .setCallback(new PCallback() {
                            @Override
                            public void onResult(Result result) {
                                if(result == Result.SUCCESS)
                                {
                                    Toast.makeText(MainActivity.this, "Paiement Effectuer", Toast.LENGTH_SHORT).show();
                                }
                                else if(result == Result.CANCEL)
                                {
                                    Toast.makeText(MainActivity.this, "Vous avez annulez le paiement", Toast.LENGTH_SHORT).show();
                                }
                                else if(result == Result.ERROR)
                                {
                                    Toast.makeText(MainActivity.this, "Erreur lors du paiement", Toast.LENGTH_SHORT).show();
                                }
                            }
                        })
                        .send();
            }
        });





    }

}
