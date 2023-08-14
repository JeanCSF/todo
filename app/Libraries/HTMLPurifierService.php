<?php
namespace App\Libraries;
use HTMLPurifier;
use HTMLPurifier_Config;

class HTMLPurifierService
{
    function html_purify($dirty_html, $config = false)
    {
        if (is_array($dirty_html)) {
            foreach ($dirty_html as $key => $val) {
                $clean_html[$key] = html_purify($val, $config);
            }
        } else {

            switch ($config) {
                case 'comment':
                    $config = \HTMLPurifier_Config::createDefault();
                    $config->set('HTML.Doctype', 'XHTML 1.0 Strict');
                    $config->set('HTML.Allowed', 'p[class|style],a[title|class|style|href],abbr[title],acronym[title],b,strong,blockquote[cite],code,em,i[class|style],span[class|style]');
                    $config->set('AutoFormat.AutoParagraph', true);
                    $config->set('AutoFormat.Linkify', true);
                    $config->set('AutoFormat.RemoveEmpty', true);
                    break;

                case false:
                    $config = \HTMLPurifier_Config::createDefault();
                    $config->set('HTML.Doctype', 'XHTML 1.0 Strict');
                    break;

                default:
                    show_error('The HTMLPurifier configuration labeled "'.htmlspecialchars($config, ENT_QUOTES, $ci->config->item('charset')).'" could not be found.');
            }

            $purifier = new \HTMLPurifier($config);
            $clean_html = $purifier->purify($dirty_html);
        }

        return $clean_html;
    }
}

/* End of htmlpurifier_helper.php */
/* Location: ./application/helpers/htmlpurifier_helper.php */