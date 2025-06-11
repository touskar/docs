package sdk.paytech.sn;

import android.content.Intent;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.os.Message;
import android.util.Log;
import android.view.KeyEvent;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.RelativeLayout;

import androidx.appcompat.app.AppCompatActivity;

public class ViewActivity extends AppCompatActivity {


    private WebView webView;
    private WebView childWebView;
    private int lastValue;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        //overridePendingTransition( R.anim.slide_in_up, R.anim.slide_out_up );
        //overridePendingTransition(R.anim.fadein, R.anim.fadeout);
        // full screen




        requestWindowFeature(Window.FEATURE_NO_TITLE);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);

        try { getSupportActionBar().hide(); } catch (Exception e) { e.printStackTrace(); }

        webView = new WebView(this);

        new PayTechWebHandler().configureWebView(this, null, getIntent().getStringExtra("url"), webView, childWebView);

        setContentView(webView);
        AndroidBug5497Workaround.assistActivity(this);

    }

    @Override
    public boolean onKeyDown(final int keyCode, final KeyEvent event) {
        if (keyCode == KeyEvent.KEYCODE_BACK) {
            if (childWebView != null) {
                webView.removeAllViews();
                childWebView = null;
                return false;
            } else {
                PayTech.callback.onResult(PCallback.Result.CANCEL);
                ViewActivity.this.finish();
            }

        }
        return super.onKeyDown(keyCode, event);
    }
}


