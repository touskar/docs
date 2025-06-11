package sdk.paytech.sn;

import android.app.Dialog;
import android.content.Context;
import android.content.DialogInterface;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.bottomsheet.BottomSheetBehavior;
import com.google.android.material.bottomsheet.BottomSheetDialog;
import com.google.android.material.bottomsheet.BottomSheetDialogFragment;

import android.util.DisplayMetrics;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.webkit.WebView;
import android.widget.FrameLayout;


public class FloatingViewActivity extends BottomSheetDialogFragment {
    private WebView webView;
    private WebView childWebView;
    private boolean progDismiss = false;
    private View contentView;
    private float topMargin = 0;

    public static FloatingViewActivity newInstance() {
        return new FloatingViewActivity();
    }

    /*@Override
    public int getTheme() {
        return  R.style.BottomSheetDialogTheme;
    }*/


    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        //setStyle(STYLE_NORMAL, R.style.AppModalStyle);
    }

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater,
                             @Nullable ViewGroup container,
                             @Nullable Bundle savedInstanceState) {

        if (contentView == null) {
            contentView = inflater.inflate(R.layout.roundedwebview, container, false);

        }

        topMargin = getArguments().getInt("topMargin");
        webView = contentView.findViewById(R.id.webview);

        new PayTechWebHandler().configureWebView((AppCompatActivity) getActivity(), this, getArguments().getString("url"), webView, childWebView);

        return contentView;
    }

    @NonNull
    @Override
    public Dialog onCreateDialog(Bundle savedInstanceState) {
        Dialog dialog = super.onCreateDialog(savedInstanceState);
        dialog.setOnShowListener(new DialogInterface.OnShowListener() {
            @Override
            public void onShow(DialogInterface dialogInterface) {
                BottomSheetDialog bottomSheetDialog = (BottomSheetDialog) dialogInterface;
                setupRatio(bottomSheetDialog);
            }
        });
        return dialog;
    }


    @Override
    public void onDismiss(DialogInterface dialog) {
        super.onDismiss(dialog);
        //getActivity().getSupportFragmentManager().beginTransaction().remove(this).commit();
        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                if(PayTech.handleDismiss){
                    PayTech.callback.onResult(PCallback.Result.CANCEL);
                }
            }
        }, 200);
    }

    private void setupRatio(BottomSheetDialog bottomSheetDialog) {
        //id = com.google.android.material.R.id.design_bottom_sheet for Material Components
        //id = android.support.design.R.id.design_bottom_sheet for support librares
        FrameLayout bottomSheet = (FrameLayout)
                bottomSheetDialog.findViewById(R.id.design_bottom_sheet);
        BottomSheetBehavior behavior = BottomSheetBehavior.from(bottomSheet);
        ViewGroup.LayoutParams layoutParams = bottomSheet.getLayoutParams();
        layoutParams.height = getBottomSheetDialogDefaultHeight();
        bottomSheet.setLayoutParams(layoutParams);
        behavior.setState(BottomSheetBehavior.STATE_EXPANDED);
    }

    private int getBottomSheetDialogDefaultHeight() {
        return (getWindowHeight() * 100 / 100) - (int) convertDpToPx(getActivity(), topMargin) + (int) convertDpToPx(getActivity(), 20);
    }

    public float convertDpToPx(Context context, float dp) {
        if (dp == 0) {
            return 0;
        }
        return dp * context.getResources().getDisplayMetrics().density;
    }

    private int getWindowHeight() {
        // Calculate window height for fullscreen use
        DisplayMetrics displayMetrics = new DisplayMetrics();
        ((AppCompatActivity) getContext()).getWindowManager().getDefaultDisplay().getMetrics(displayMetrics);
        return displayMetrics.heightPixels;
    }


    public boolean isProgDismiss() {
        return progDismiss;
    }

    public void setProgDismiss(boolean progDismiss) {
        this.progDismiss = progDismiss;
    }

    public WebView getWebView() {
        return webView;
    }

}