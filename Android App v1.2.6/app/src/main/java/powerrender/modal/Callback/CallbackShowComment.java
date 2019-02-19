package powerrender.modal.Callback;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

import powerrender.modal.Comment;

/**
 * Created by SUPRIYANTO on 27/10/2018.
 */

public class CallbackShowComment implements Serializable {
    public List<Comment> data = new ArrayList<>();
}
