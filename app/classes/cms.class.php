<?php
class CMS
{
  /**
   * Initialize CMS.
   */
  public static function Initialize($GET)
  {
    $tpl = (isset($GET['page']) ? $GET['page'] : '');

    if (strlen($tpl) == 1 || strlen($tpl) == 0) {
      self::LoadTemplate('index');
    } else {
      self::LoadTemplate($tpl);
    }
  }

  public static function LoadTemplate($tpl)
  {
    if (!file_exists(sprintf('app/templates/%s.tpl.php', $tpl)))
      header('Location: '.BASE_URL.'/?page=index');

    // Templates
    $data = Pages::$main;

    if (isset($data[$tpl]['title'])) {
      $title = $data[$tpl]['title'];
      $subtitle = $data[$tpl]['subtitle'];
      $iconClass = $data[$tpl]['iconClass'];
    }

    ob_start();
    include 'app/templates/header.tpl.php';
    include sprintf('app/templates/%s.tpl.php', $tpl);
    include 'app/templates/footer.tpl.php';

    $temporary = ob_get_contents();
    ob_end_clean();

    $temporary = CMS::sanitize_output($temporary);
    //$temporary .= "\r\n <!-- NO CACHE --> ";

    echo $temporary;

    unset($temporary);
  }

  /**
   * minimise html output
   */
  public static function sanitize_output($buffer)
  {
    // Commented for debugging purposes
    
    /*
    $search = [
      '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
      '/[^\S ]+\</s',  // strip whitespaces before tags, except space
      '/(\s)+/s'       // shorten multiple whitespace sequences
    ];

    $replace = [
      '>',
      '<',
      '\\1'
    ];

    $buffer = preg_replace($search, $replace, $buffer);

    $buffer = str_replace("\t", "", $buffer);
    */

    return $buffer;
  }
}
