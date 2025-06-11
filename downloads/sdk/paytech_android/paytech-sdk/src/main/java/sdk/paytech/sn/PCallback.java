package sdk.paytech.sn;

/**
 * Created by moussandour on 12/10/2017.
 */

public interface PCallback {
    public enum Result {
        SUCCESS,
        CANCEL,
        ERROR
    }

    public void onResult(Result result);
}
