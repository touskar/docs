package com.pax.demo.modules.ped;

import android.os.Bundle;
import android.text.InputType;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.pax.demo.R;
import com.pax.demo.base.BasePedFragment;

public class ShowStrFragment extends BasePedFragment {

    private TextView writeview;
    private Button button;
    private EditText editText;
    private TextView textview;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        super.onCreateView(inflater, container, savedInstanceState);
        View view = inflater.inflate(R.layout.fragment_ped_base_layout, container, false);
        writeview = (TextView) view.findViewById(R.id.fragment_ped_base_result);
        textview = (TextView) view.findViewById(R.id.fragment_ped_base_title);
        textview.setText(getString(R.string.ped_display_content));
        button = (Button) view.findViewById(R.id.fragment_ped_base_action);
        editText = (EditText) view.findViewById(R.id.fragment_ped_base_edit_1);
        editText.setInputType(InputType.TYPE_CLASS_TEXT);
        editText.setText("KEYBOARD TEST");
        view.findViewById(R.id.fragment_ped_base_edit_2).setVisibility(View.GONE);
        button.setOnClickListener(new OnClickListener() {

            @Override
            public void onClick(View arg0) {
                String str = editText.getText().toString().trim();
                boolean flag = PedTester.getInstance(pedType).showStr(str);
                if (flag) {
                    writeview.setText(getString(R.string.ped_display_success));
                } else {
                    writeview.setText(getString(R.string.ped_display_fail));
                }
            }
        });
        return view;
    }
}
