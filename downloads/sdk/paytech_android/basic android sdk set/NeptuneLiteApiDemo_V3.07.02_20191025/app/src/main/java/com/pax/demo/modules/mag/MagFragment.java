package com.pax.demo.modules.mag;

import java.util.Arrays;

import android.annotation.SuppressLint;
import android.app.Fragment;
import android.app.FragmentManager;
import android.app.FragmentTransaction;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.GridView;
import android.widget.LinearLayout;

import com.pax.demo.R;
import com.pax.demo.base.BaseFragment;
import com.pax.demo.util.BackListAdapter;

public class MagFragment extends BaseFragment implements OnItemClickListener {
    private LinearLayout screenLayout;
    private GridView consoleGridView;
    private BackListAdapter adapter;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_main, container, false);
        screenLayout = (LinearLayout) view.findViewById(R.id.fragment_screen);
        consoleGridView = (GridView) view.findViewById(R.id.fragment_gridview);
        adapter = new BackListAdapter(Arrays.asList(getResources().getStringArray(R.array.Mag)), getActivity());
        consoleGridView.setAdapter(adapter);
        consoleGridView.setOnItemClickListener(this);
        return view;
    }

    @SuppressLint("NewApi")
    @Override
    public void onDestroyView() {
        if (getChildFragmentManager().getBackStackEntryCount() > 0) {
            getChildFragmentManager().popBackStackImmediate();
        }
        super.onDestroyView();
    }

    @Override
    public void onItemClick(AdapterView<?> arg0, View view, int position, long id) {
        adapter.setPos(position);
        adapter.notifyDataSetChanged();
        switch (position) {
            case 0:
                fragmentSelect(new ReadFragment());
                break;
            default:
                break;
        }

    }

}
