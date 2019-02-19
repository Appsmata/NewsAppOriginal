package powerrender.modal.Callback;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

import powerrender.modal.News;

/**
 * Created by SUPRIYANTO on 02/08/2018.
 */

public class CallbackNews implements Serializable{
    public int total = -1;
    public List<News> data = new ArrayList<>();
}
