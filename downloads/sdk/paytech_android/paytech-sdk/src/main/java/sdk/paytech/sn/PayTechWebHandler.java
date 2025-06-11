package sdk.paytech.sn;

import android.content.Intent;
import android.net.Uri;
import android.os.Build;
import android.os.Handler;
import android.os.Message;
import android.util.Log;
import android.view.ViewGroup;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.RelativeLayout;

import androidx.appcompat.app.AppCompatActivity;

public class PayTechWebHandler {

    private WebView webView;
    private WebView childWebView;




    public  void  configureWebView(final AppCompatActivity activity, final FloatingViewActivity floatingViewActivity, String url, WebView _webView, WebView _childWebView){

        PayTech.handleDismiss = true;
        webView = _webView;
        childWebView = _childWebView;

        //webView setting
        WebSettings webSettings = webView.getSettings();
        webSettings.setJavaScriptEnabled(true);
        webSettings.setLoadWithOverviewMode(true);
        webSettings.setUseWideViewPort(true);
        webSettings.setJavaScriptCanOpenWindowsAutomatically(true);
        webSettings.setSupportMultipleWindows(true);
        webSettings.setNeedInitialFocus(true);
        webView.setVerticalScrollBarEnabled(false);

        webView.addJavascriptInterface(new JAndroid(activity, floatingViewActivity), "JAndroid");

        webView.loadUrl(url);


        webView.setWebViewClient(new WebViewClient() {
            @Override
            public boolean shouldOverrideUrlLoading(WebView view, String url) {

                Log.i("Overide url", url);

                if (PayTech.MOBILE_CANCEL_URL.equals(url)) {

                    PayTech.handleDismiss = false;
                    if(floatingViewActivity != null){
                        floatingViewActivity.setProgDismiss(true);
                        floatingViewActivity.dismiss();
                    }
                    else{
                        activity.finish();
                    }

                    final AppCompatActivity _activity = floatingViewActivity != null ? (AppCompatActivity) floatingViewActivity.getActivity() : activity;

                    new Handler().postDelayed(new Runnable() {
                        @Override
                        public void run() {
                            _activity.runOnUiThread(new Runnable() {
                                @Override
                                public void run() {
                                    PayTech.callback.onResult(PCallback.Result.CANCEL);
                                }
                            });
                        }
                    }, 200);

                    return false;
                }
                else if (PayTech.MOBILE_SUCCESS_URL.equals(url)) {

                    PayTech.handleDismiss = false;
                    if(floatingViewActivity != null){
                        floatingViewActivity.setProgDismiss(true);
                        floatingViewActivity.dismiss();
                    }
                    else{
                        activity.finish();
                    }

                    final AppCompatActivity _activity = floatingViewActivity != null ? (AppCompatActivity) floatingViewActivity.getActivity() : activity;


                    new Handler().postDelayed(new Runnable() {
                        @Override
                        public void run() {
                            _activity.runOnUiThread(new Runnable() {
                                @Override
                                public void run() {
                                    PayTech.callback.onResult(PCallback.Result.SUCCESS);
                                }
                            });
                        }
                    }, 200);

                    return false;
                }

                Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                view.getContext().startActivity(intent);
                return true;
            }
        });

        webView.setWebChromeClient(new WebChromeClient() {
            @Override
            public boolean onCreateWindow(WebView view, boolean isDialog, boolean isUserGesture, Message resultMsg) {
                webView.removeAllViews();
                childWebView = new WebView(webView.getContext());
                WebSettings webSettings = childWebView.getSettings();
                webSettings.setJavaScriptEnabled(true);
                webSettings.setJavaScriptCanOpenWindowsAutomatically(true);
                webSettings.setSupportMultipleWindows(true);
                webSettings.setNeedInitialFocus(true);

                childWebView.setWebViewClient(new WebViewClient());
                // Create dynamically a new view
                childWebView.setLayoutParams(new RelativeLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT));

                childWebView.setWebChromeClient(new WebChromeClient() {
                    @Override
                    public void onCloseWindow(WebView window) {
                        webView.removeAllViews();
                        childWebView = null;
                    }
                });
                webView.addView(childWebView);

                WebView.WebViewTransport transport = (WebView.WebViewTransport) resultMsg.obj;
                transport.setWebView(childWebView);
                resultMsg.sendToTarget();
                return true;
            }
        });
    }

}
