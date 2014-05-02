package infs3202.practical4;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.ResponseBody;

/**
 * User: Blake
 * Date: 28/04/2014
 * Time: 12:38 PM
 */
@Controller
public class HomeController {

    @RequestMapping("/test")
    public @ResponseBody
    String simple() {
        return "Test test test.";
    }
}
