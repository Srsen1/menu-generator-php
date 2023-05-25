<?php

class Menu
{
    private $html_input;


    public function __construct($html_input)
    {
        $this->html_input = $html_input;
    }


    private function get_raw_menu($html_input)
    {
        $result = array();
        $startOffset = 0;
        $endOffset = 0;

        while (TRUE) {
            $start = strpos($html_input, "<h", $startOffset);
            $end = strpos($html_input, "</h", $endOffset);
            $weight = $html_input[$start+2];
            $semiResult = "";

            if(!$start){
                break;
            }

            for ($i=$start+4; $i < $end; $i++) { 
                $semiResult .= $html_input[$i];
            }
            $startOffset = $end;
            $endOffset = $end+3;
            array_push($result, array(intval($weight), $semiResult));
        }
        return $result;
    }


    public function get_menu()
    {
        $menu = "<ul>";
        $level = 1;

        foreach ($this->get_raw_menu($this->html_input) as $line) {
            $get_to_level = $line[0];
            $first = TRUE;
            $change = TRUE;

            while(TRUE) {
                if(!$first){
                    $change = FALSE;
                }
                if($get_to_level > $level){
                    $menu .= "<ul>";
                    $level++;
                    $change = TRUE;
                }elseif($get_to_level < $level){
                    $menu .= "</ul></li>";
                    $level--;
                    $change = TRUE;
                }else{
                    $menu .= "<li>" . $line[1];
                    if(!$change){
                        $menu .= "</li>";
                    }
                    break;
                }
                $first = FALSE;
            }
        }
        while($level-1 > 0){
            $menu .= "</ul></li>";
            $level--;
        }
        $menu .= "</ul>";
        return $menu;
    }
}


$test = new Menu
    ('
        <h1>My Article</h1>

        <h2>Introduction</h2>
        
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec interdum diam ac augue commodo, id condimentum est vulputate. In tristique, felis non varius tempus, justo dolor convallis lectus, et lacinia ex nulla nec purus. Aenean id tempor tellus. Nam nec sem in nunc elementum aliquam vitae a risus. Proin sit amet lectus risus. Morbi bibendum dapibus orci. Mauris maximus ante mi, sit amet tincidunt ex dapibus non. Integer vitae pharetra est.</p>
        
        <h2>Main Section</h2>
        
        <h3>Subsection 1</h3>
        
        <p>Aliquam tristique leo a tempor malesuada. Nunc interdum quam ut ante vulputate, at efficitur metus vulputate. Nulla facilisi. Aliquam feugiat velit id purus suscipit malesuada. Fusce vehicula purus quis nunc fringilla fringilla. Quisque aliquet erat sed neque tempor, ut interdum lectus dignissim. Cras blandit, metus vitae aliquet convallis, nisl sapien fringilla quam, sed sollicitudin nunc tellus nec nisi.</p>
        
        <h3>Subsection 2</h3>
        
        <p>Vestibulum gravida aliquam massa, sit amet interdum justo ultrices ac. Integer efficitur venenatis tellus ac condimentum. Maecenas id nunc enim. Nam et iaculis nulla. Nulla facilisi. Nam elementum est non leo ultrices sollicitudin. Fusce eleifend neque eu nisl posuere lobortis. Vivamus eu urna nec dui consectetur bibendum sed nec sem.</p>
        
        <h4>Sub-subsection 2.1</h4>
        
        <p>Nullam interdum consectetur tortor ut tempor. Morbi venenatis, turpis ac fringilla varius, urna elit interdum turpis, eu lobortis sem ligula at enim. Quisque euismod turpis urna, sed suscipit urna euismod vitae. Sed tempus tempor ex, nec cursus nisi. Mauris fermentum ligula non sapien commodo, a pulvinar tortor ullamcorper. Phasellus tempor, nunc sit amet tempor venenatis, risus elit ullamcorper elit, nec hendrerit risus velit non dolor. In ac mauris nec purus fringilla sagittis. Curabitur lobortis elit et nulla molestie, at pulvinar massa faucibus. Suspendisse vel velit non tellus viverra feugiat.</p>
        
        <h2>Conclusion</h2>
        
        <p class="author">Written by John Doe</p>
        <p class="date">Published on May 25, 2023</p>
    ');

echo $test->get_menu();
