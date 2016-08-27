<?php

namespace Padosoft\Support\Test;

class HelpersTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    /**
     * @param $expected
     * @return bool
     */
    protected function expectedIsAnException($expected)
    {
        if (is_array($expected)) {
            return false;
        }

        return strpos($expected, 'Exception') !== false
        || strpos($expected, 'PHPUnit_Framework_') !== false
        || strpos($expected, 'TypeError') !== false;
    }

    /**
     * GetFavicon Tests.
     */
    public function test_getFaviconUrl()
    {
        $favIcon = getFaviconUrl('http://youtube.com/');
        $this->assertEquals("http://www.google.com/s2/favicons?domain=youtube.com/",$favIcon);
    }
    /**
     * GetFavicon Tests.
     */
    public function test_getFaviconImgTag()
    {
        $favIcon = getFaviconImgTag('http://youtube.com/');
        $this->assertEquals(
            '<img src="http://www.google.com/s2/favicons?domain=youtube.com/"  />',
            $favIcon
        );
    }
    public function test_getFaviconImgTag_with_attributes()
    {
        $favIcon = getFaviconImgTag('http://youtube.com/', [
            'class' => 'favImg',
        ]);
        $this->assertEquals(
            '<img src="http://www.google.com/s2/favicons?domain=youtube.com/" class="favImg" />',
            $favIcon
        );
    }
    /**
     * Get QRcode Tests.
     */
    public function test_getQRcodeUrl()
    {
        $QRcode = getQRcodeUrl('ngfw Recipe');
        $this->assertEquals("http://chart.apis.google.com/chart?chs=150x150&cht=qr&chl=ngfw+Recipe",$QRcode);
    }
    /**
     * Get QRcode Tests.
     */
    public function test_getQRcode()
    {
        $QRcode = getQRcode('ngfw Recipe');
        $this->assertEquals(
            '<img src="http://chart.apis.google.com/chart?chs=150x150&cht=qr&chl=ngfw+Recipe"  />',
            $QRcode
        );
    }
    public function test_getQRcode_with_attributes()
    {
        $QRcode = getQRcode(
            'ngfw Recipe',
            $width = 350,
            $height = 350,
            $attributes = [
                'class' => 'QRCode',
            ]
        );
        $this->assertEquals(
            '<img src="http://chart.apis.google.com/chart?chs=350x350&cht=qr&chl=ngfw+Recipe" class="QRCode" />',
            $QRcode
        );
    }

    /**
     * Get Gravatar Tests.
     */
    public function test_getGravatarUrl()
    {
        $Gravatar = gravatarUrl('gejadze@gmail.com');
        $this->assertEquals("http://www.gravatar.com/avatar.php?gravatar_id=9d9d478c3b65d4046a84cf84b4c8bf46&default=mm&size=80&rating=g",$Gravatar);
    }

    /**
     * Get Gravatar Tests.
     */
    public function test_getGravatar()
    {
        $Gravatar = gravatar('gejadze@gmail.com');
        $this->assertEquals(
            '<img src="http://www.gravatar.com/avatar.php?gravatar_id=9d9d478c3b65d4046a84cf84b4c8bf46&default=mm&size=80&rating=g" width="80px" height="80px"  />',
            $Gravatar
        );
    }
    public function test_getGravatar_with_attributes()
    {
        $Gravatar = gravatar(
            'gejadze@gmail.com',
            $size = 200,
            $default = 'monsterid',
            $rating = 'x',
            $attributes = [
                'class' => 'Gravatar',
            ]
        );
        $this->assertEquals(
            '<img src="http://www.gravatar.com/avatar.php?gravatar_id=9d9d478c3b65d4046a84cf84b4c8bf46&default=monsterid&size=200&rating=x" width="200px" height="200px" class="Gravatar" />',
            $Gravatar
        );
    }

    /**
     * Colors.
     */
    public function test_hex2rgb()
    {
        $rgb = hex2rgb('#FFF');
        $this->assertEquals(
            'rgb(255, 255, 255)',
            $rgb
        );
    }

    /**
     * colors
     */
    public function test_rgb2hex()
    {
        $hex = rgb2hex(123,123,123);
        $this->assertEquals(
            '#7b7b7b',
            $hex
        );
    }


    /**
     * Check if request is on https.
     *
     */
    public function test_isHttps()
    {
        $oldValue = false;
        if(array_key_exists_safe($_SERVER, 'HTTPS')){
            $oldValue = $_SERVER['HTTPS'];
        }

        $_SERVER['HTTPS'] = true;
        $isHttps = isHttps();
        $this->assertTrue($isHttps);

        $_SERVER['HTTPS'] = false;
        $isHttps = isHttps();
        $this->assertFalse($isHttps);

        $_SERVER['HTTPS'] = $oldValue;
    }

    /**
     * Check if request is ajax.
     *
     */
    public function test_isAjax()
    {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        $isAjax = isAjax();
        $this->assertTrue($isAjax);
    }

    /**
     * Numbers.
     */
    public function test_isNumberOdd()
    {
        $number = 5;
        $isNumberOdd = isNumberOdd($number);
        $this->assertTrue($isNumberOdd);
    }
    public function test_isNumberEven()
    {
        $number = 8;
        $isNumberEven = isNumberEven($number);
        $this->assertTrue($isNumberEven);
    }
    /**
     * Current URL.
     */
    public function test_getCurrentURL()
    {
        $_SERVER['HTTPS'] = true;
        $_SERVER['REQUEST_URI'] = 'example.com';
        $currentURL = getCurrentURL();
        $this->assertEquals('https://example.com',$currentURL);

        $_SERVER['HTTPS'] = false;
        $currentURL = getCurrentURL();
        $this->assertEquals('http://example.com',$currentURL);
    }

    /**
     * Test mobile device.
     */
    public function test_isMobile()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_0 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8A293 Safari/6531.22.7';
        $isMobile = isMobile();
        $this->assertTrue($isMobile);
    }
    /**
     * Detect user browser.
     */
    public function test_getBrowser()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_0 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8A293 Safari/6531.22.7';
        $browser = getBrowser();
        $this->assertInternalType('string', $browser);
    }

    /**
     * Curl.
     */
    public function test_curl()
    {
        $testCurl = curl('https://api.ipify.org');
        $ipCheck = filter_var($testCurl, FILTER_VALIDATE_IP)!==false;
        $this->assertTrue($ipCheck);
        $testCurlPOST = curl('http://jsonplaceholder.typicode.com/posts', $method = 'POST', $data = [
            'title'  => 'foo',
            'body'   => 'bar',
            'userId' => 1,
        ]);
        $POST_obj = json_decode($testCurlPOST);
        $this->assertInternalType('object', $POST_obj);
        $testCurlHeaderAndReturnInfo = curl('http://jsonplaceholder.typicode.com/posts', $method = 'GET', $data = false, $header = [
            'Accept' => 'application/json',
        ], $returnInfo = true);
        $this->assertInternalType('array', $testCurlHeaderAndReturnInfo);
        $this->assertInternalType('array', $testCurlHeaderAndReturnInfo['info']);
        $this->assertInternalType('string', $testCurlHeaderAndReturnInfo['contents']);
    }
    /**
     * Expend shortened URLs.
     */
    public function test_expandShortUrl()
    {
        $expandedURL = expandShortUrl('https://goo.gl/rvDnMX');
        $this->assertEquals(
            'https://github.com/ngfw/Recipe',
            $expandedURL
        );
    }

    /**
     * Shorten the URL.
     */
    public function test_getTinyUrl()
    {
        $TinyUrl = getTinyUrl('https://github.com/ngfw/Recipe');
        $this->assertEquals(
            'http://tinyurl.com/h2nchjh',
            $TinyUrl
        );
    }

    /**
     * Custom Debug.
     */
    public function test_debug()
    {
        $string = 'Test me';
        ob_start();
        debug($string);
        $debug = ob_get_clean();
        $this->assertInternalType('string', $debug);
    }
}
