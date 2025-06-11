package sdk.paytech.sn;

import android.content.Intent;
import android.net.Uri;
import android.webkit.JavascriptInterface;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

class JAndroid {

    AppCompatActivity activity;
    FloatingViewActivity floatingViewActivity;

    public JAndroid(AppCompatActivity activity, FloatingViewActivity floatingViewActivity) {
        this.floatingViewActivity = floatingViewActivity;
        this.activity = activity;
    }

    @JavascriptInterface
    public void openDial(final String dialCode) {
        final AppCompatActivity _activity = floatingViewActivity != null ? (AppCompatActivity) floatingViewActivity.getActivity() : activity;


        _activity.runOnUiThread(new Runnable() {
            @Override
            public void run() {
                Intent intent = new Intent(Intent.ACTION_DIAL);
                // Send phone number to intent as data
                intent.setData(Uri.parse("tel:" + Uri.encode(dialCode)));
                // Start the dialer app activity with number
                _activity.startActivity(intent);
            }
        });
    }

    @JavascriptInterface
    public void onPaymentStart(final String args) {
        final AppCompatActivity _activity = floatingViewActivity != null ? (AppCompatActivity) floatingViewActivity.getActivity() : activity;


        _activity.runOnUiThread(new Runnable() {
            @Override
            public void run() {
                Toast.makeText(_activity, "onPaymentStart", Toast.LENGTH_SHORT).show();
            }
        });
    }
}
