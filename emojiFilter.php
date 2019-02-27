<?php
/**
 * 昵称（表情包）处理方法
 *
 * User: 原子酱
 * Date: 2019/2/27
 * Time: 10:43
 */

class emojiFilter
{

    /**
     * 微信昵称
     *
     * @param string $nickname
     * @return mixed|string
     */
    public function nicknameFilter($nickname = '')
    {
        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $nickname = preg_replace($regexEmoticons, '', $nickname);

        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $nickname = preg_replace($regexSymbols, '', $nickname);

        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $nickname = preg_replace($regexTransport, '', $nickname);

        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        $nickname = preg_replace($regexMisc, '', $nickname);

        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $nickname = preg_replace($regexDingbats, '', $nickname);

        return $this->emojiFilter($nickname);
    }

    protected function emojiFilter($str)
    {
        if ($str) {
            $name = $str;
            $name = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '', $name);
            $name = preg_replace('/xE0[x80-x9F][x80-xBF]‘.‘|xED[xA0-xBF][x80-xBF]/S', '?', $name);
            $return = json_decode(preg_replace("#(\\\ud[0-9a-f]{3})#ie", "", json_encode($name)));

        } else {
            $return = '';
        }
        return $return;

    }
}