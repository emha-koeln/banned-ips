<?php

/**
 * CoordImg.php
 * Part of banned-ips
 * v 0.1.5.alpha
 * (c) 2021 emha.koeln
 * License: GPLv2+
 */
/**
 *
 * @package banned-ips
 * @author emha.koeln
 */

class CoordImg
{

    // Image
    var $vImage;

    // Imagesize
    var $iImgSizeX;
    var $iImgSizeY;

    // HtmlMap
    var $sHtmlMap = "";

    // CoordRange
    var $iCoordRangeX;                                   // needed?
    var $iCoordRangeY;                                  // needed?
    
    // Data
    var $aData = array();
    var $iDataCount;

    // Data
    var $iX0;
    var $iXmax;                                         // needed?
    // var $iXcount;                                    //needed?
    var $iY0;

    var $iYmax;                                         // needed?
    // var $iYcount;                                    //needed?

    // Borders
    var $iBorderPercentage = 5 / 100;                   // will be recalculated for Bottom?
    var $iLeftBorder;
    var $iRightBorder;
    var $iTopBorder;
    var $iBottomBorder;

    // Axis
    
    // Coords
    var $iCoordsX0;
    var $iCoordsXmax;                                   // needed?
    var $iCoordsXlength;                                // needed?
    var $iCoordsY0;

    var $iCoordsYmax;
 // needed?
    var $iCoordsYlength;
 // needed
    var $iCoordsFont = 2;

    var $iCoordsLableFont = 3;

    // Colors
    var $vBgColor;
    var $vBgColor_r = 224;
    var $vBgColor_b = 224;
    var $vBgColor_g = 224;
    
    var $vTextColor;
    var $vTextColor_r = 0;
    var $vTextColor_b = 0;
    var $vTextColor_g = 0;

    var $vAxisColor;
    var $vAxisColor_r = 64;
    var $vAxisColor_b = 64;
    var $vAxisColor_g = 64;

    var $vGraphColor;
    var $vGraphColor_r = 192;
    var $vGraphColor_b = 0;
    var $vGraphColor_g = 0;

    // Debug
    var $bDebug = FALSE;
    var $iDebugFont = 4;
    var $iNextDebugLine = 0;

    /**
     *
     * @param int $iImgSizeX
     *            Width of the Image
     * @param int $iImgSizeY
     *            Height of the Image
     * @param array $aData
     *            Data as x => y
     * @param string $iCoordRangeX
     *            Range X, if set to 0 it will be calculated from $aData
     *            (not done)
     * @param string $iCoordRangeX
     *            Range Y, if set to 0 it will be calculated from $aData
     *            (not done)
     */
    public function __construct(int $iImgSizeX, int $iImgSizeY, array $aData, $iCoordRangeX = 0, $iCoordRangeY = 0)
    {
        $this->iImgSizeX = $iImgSizeX;
        $this->iImgSizeY = $iImgSizeY;
        $this->iCoordRangeX = $iCoordRangeX;
        $this->iCoordRangeY = $iCoordRangeY;
        $this->aData = $aData;
        $this->iDataCount = count($this->aData);
        
        $this->vImage = @imagecreate($this->iImgSizeX, $this->iImgSizeY) or die("Cannot Initialize new GD image stream");
        
        // if($this->iCoordRangeX = 0 || $this->iCoordRangeX = ""){
        // $this->_calculate_CoordX;
        // }
        // if($this->iCoordRangeY = 0 || $this->iCoordRangeY = ""){
        // $this->_calculate_CoordX;
        // }
    }

    /**
     *
     * @return array ImageSize as Array x,y
     */
    public function get_ImgSize()
    {
        return array(
            $this->iImgSizeX,
            $this->iImgSizeY
        );
    }

    /**
     *
     * @return array CoordSize as Array x,y
     */
    public function get_CoordSize()
    {
        return array(
            $this->iCoordSizeX,
            $this->iCoordSizeY
        );
    }

    /**
     *
     * @return array CoordImage
     */
    public function get_Image()
    {
        $this->_createImage();
        return $this->vImage;
    }

    public function set_BgColor(int $Red, int $Green, int $Blue)
    {
        $this->vBgColor_r = $Red;
        $this->vBgColor_g = $Green;
        $this->vBgColor_b = $Blue;
    }

    public function set_TxtColor(int $Red, int $Green, int $Blue)
    {
        $this->vTextColor_r = $Red;
        $this->vTextColor_g = $Green;
        $this->vTextColor_b = $Blue;
    }

    public function set_AxisColor(int $Red, int $Green, int $Blue)
    {
        $this->vAxisColor_r = $Red;
        $this->vAxisColor_g = $Green;
        $this->vAxisColor_b = $Blue;
    }

    public function set_GraphColor(int $Red, int $Green, int $Blue)
    {
        $this->vGraphColor_r = $Red;
        $this->vGraphColor_g = $Green;
        $this->vGraphColor_b = $Blue;
    }

    public function set_transparency()
    {
        // $this->vImage;
        imagesavealpha($this->vImage, true);
        $color = imagecolorallocatealpha($this->vImage, 0, 0, 0, 127);
        imagefill($this->vImage, 0, 0, $color);
        // imagepng($img, 'test.png');
    }

    /**
     *
     * @param bool $Debug
     */
    public function set_Debug($bDebug)
    {
        $this->bDebug = $bDebug;
    }

    /**
     */
    public function get_NextDebugLine()
    {
        $this->iNextDebugLine = $this->iNextDebugLine + $this->iDebugFont * 3;
        return $this->iNextDebugLine;
    }

    /**
     */
    public function get_HtmlMap()
    {
        
        // $this->sHtmlMap = '<map name="Map">';
        // $this->sHtmlMap .= '<area shape="rect" coords="0,0,800,50"';
        // $this->sHtmlMap .= ' href="" alt="R1" title="R1">';
        
        // //$this->sHtmlMap .= ' onmouseover="this.title=\'NEW TITEL R1\';">';
        // $this->sHtmlMap .= '<area shape="circle" coords="100,50,100"';
        // $this->sHtmlMap .= ' href="" alt="C2" title="C2">';
        // //$this->sHtmlMap .= ' onmouseover="this.title=\'NEW TITEL C2\';">';
        // $this->sHtmlMap .= '</map>';
        return $this->sHtmlMap;
    }

    private function _createImage()
    {
        // $this->vImage = @imagecreate ( $this->iImgSizeX, $this->iImgSizeY ) or die ( "Cannot Initialize new GD image stream" );
        $this->vBgColor = imagecolorallocate($this->vImage, $this->vBgColor_r, $this->vBgColor_g, $this->vBgColor_b);
        $this->vTextColor = imagecolorallocate($this->vImage, $this->vTextColor_r, $this->vTextColor_g, $this->vTextColor_b);
        $this->vAxisColor = imagecolorallocate($this->vImage, $this->vAxisColor_r, $this->vAxisColor_g, $this->vAxisColor_b);
        $this->vGraphColor = imagecolorallocate($this->vImage, $this->vGraphColor_r, $this->vGraphColor_g, $this->vGraphColor_b);
        
        $this->sHtmlMap = '<map name="Map">' . PHP_EOL;
        
        $this->_create_Borders();
        $this->_create_Coords();
        $this->_plot_Graph();
        
        $this->sHtmlMap .= '</map>' . PHP_EOL;
        
        // if ($this->bDebug){
        // ImageString ( $this->vImage, $this->iDebugFont, 60, $this->get_NextDebugLine(), "x: " . $this->iImgSizeX, $this->vTextColor );
        // ImageString ( $this->vImage, $this->iDebugFont, 60, $this->get_NextDebugLine(), "y: " . $this->iImgSizeY, $this->vTextColor );
        // ImageString ( $this->vImage, $this->iDebugFont, 60, $this->get_NextDebugLine(), "l: " . $this->iLeftBorder, $this->vTextColor );
        // ImageString ( $this->vImage, $this->iDebugFont, 60, $this->get_NextDebugLine(), "r: " . $this->iRightBorder, $this->vTextColor );
        // ImageString ( $this->vImage, $this->iDebugFont, 60, $this->get_NextDebugLine(), "t: " . $this->iTopBorder, $this->vTextColor );
        // ImageString ( $this->vImage, $this->iDebugFont, 60, $this->get_NextDebugLine(), "b: " . $this->iBottomBorder, $this->vTextColor );
        // }
    }

    private function _create_Borders()
    {
        if (is_int($this->iXmax)) {
            // Default Borders
            $this->iLeftBorder = $this->iImgSizeX * $this->iBorderPercentage;
            $this->iRightBorder = $this->iImgSizeX * $this->iBorderPercentage;
            $this->iTopBorder = $this->iImgSizeY * $this->iBorderPercentage + 5;
            $this->iBottomBorder = $this->iImgSizeY * $this->iBorderPercentage;
        } else { // Date & Time
                 // Default Borders
            $this->iLeftBorder = $this->iImgSizeX * $this->iBorderPercentage;
            $this->iRightBorder = $this->iImgSizeX * $this->iBorderPercentage + 15;
            $this->iTopBorder = $this->iImgSizeY * $this->iBorderPercentage + 5;
            $this->iBottomBorder = $this->iImgSizeY * $this->iBorderPercentage + 30;
        }
    }

    private function _create_Coords()
    {
        $this->_calculate_CoordsX();
        $this->_calculate_CoordsY();
        $this->_label_CoordsX();
        $this->_label_CoordsY();
        
        // X Axis
        imageline($this->vImage, $this->iCoordsX0 - 5, $this->iCoordsY0, $this->iCoordsXmax, $this->iCoordsY0, $this->vAxisColor);
        
        // X Arrows
        // -
        imageline($this->vImage, $this->iCoordsXmax + $this->iRightBorder / 2, $this->iCoordsY0, $this->iCoordsXmax, $this->iCoordsY0, $this->vAxisColor);
        // \
        imageline($this->vImage, $this->iCoordsXmax + $this->iRightBorder / 2, $this->iCoordsY0, $this->iCoordsXmax + $this->iRightBorder / 2 - 5, $this->iCoordsY0 - 2, $this->vAxisColor);
        // /
        imageline($this->vImage, $this->iCoordsXmax + $this->iRightBorder / 2, $this->iCoordsY0, $this->iCoordsXmax + $this->iRightBorder / 2 - 5, $this->iCoordsY0 + 2, $this->vAxisColor);
        
        // Y Axis
        imageline($this->vImage, $this->iCoordsX0, $this->iCoordsYmax, $this->iCoordsX0, $this->iCoordsY0 + 5, $this->vAxisColor);
        
        // Y Arrows
        // |
        imageline($this->vImage, $this->iCoordsX0, $this->iCoordsYmax - $this->iTopBorder / 2, $this->iCoordsX0, $this->iCoordsYmax, $this->vAxisColor);
        // \
        imageline($this->vImage, $this->iCoordsX0, $this->iCoordsYmax - $this->iTopBorder / 2, $this->iCoordsX0 + 2, $this->iCoordsYmax - $this->iTopBorder / 2 + 5, $this->vAxisColor);
        // /
        imageline($this->vImage, $this->iCoordsX0, $this->iCoordsYmax - $this->iTopBorder / 2, $this->iCoordsX0 - 2, $this->iCoordsYmax - $this->iTopBorder / 2 + 5, $this->vAxisColor);
    }

    private function _calculate_CoordsX()
    {
        // TODO: Only for Date-Time
        $this->iX0 = key($this->aData);
        end($this->aData);
        $this->iXmax = key($this->aData);
        // $this->iXcount = count($this->aData);
        
        $this->iCoordsX0 = $this->iLeftBorder;
        $this->iCoordsXmax = $this->iImgSizeX - $this->iRightBorder;
        $this->iCoordsXlength = $this->iCoordsXmax - $this->iCoordsX0;
        
        if ($this->bDebug) {
            ImageString($this->vImage, $this->iDebugFont, 60, $this->get_NextDebugLine(), "X0 " . $this->iX0, $this->vTextColor);
            ImageString($this->vImage, $this->iDebugFont, 60, $this->get_NextDebugLine(), "Xmax " . $this->iXmax, $this->vTextColor);
            ImageString($this->vImage, $this->iDebugFont, 60, $this->get_NextDebugLine(), "DataCount " . $this->iDataCount, $this->vTextColor);
        }
    }

    private function _calculate_CoordsY()
    {
        $this->iY0 = 0;
        $this->iYmax = max(array_values($this->aData));
        
        $this->iCoordsY0 = $this->iImgSizeY - $this->iBottomBorder;
        $this->iCoordsYmax = $this->iTopBorder;
        $this->iCoordsYlength = $this->iCoordsY0 - $this->iCoordsYmax;
        
        if ($this->bDebug) {
            ImageString($this->vImage, $this->iDebugFont, 60, $this->get_NextDebugLine(), "fistY " . $this->iY0, $this->vTextColor);
            ImageString($this->vImage, $this->iDebugFont, 60, $this->get_NextDebugLine(), "lastY " . $this->iYmax, $this->vTextColor);
        }
    }

    private function _label_CoordsX()
    {
        if (is_int($this->iXmax)) {
            // X0
            imageline($this->vImage, $this->iCoordsX0, $this->iCoordsY0, $this->iCoordsX0, $this->iCoordsY0 + 5, $this->vAxisColor);
            ImageString($this->vImage, $this->iCoordsFont, $this->iCoordsX0 - 3, $this->iCoordsY0 + $this->iBottomBorder / 3, $this->iX0, $this->vAxisColor);
            
            // middle
            imageline($this->vImage, $this->iCoordsXlength / 2, $this->iCoordsY0, $this->iCoordsXlength / 2, $this->iCoordsY0 + 5, $this->vAxisColor);
            ImageString($this->vImage, $this->iCoordsFont, $this->iCoordsXlength / 2 - 3, $this->iCoordsY0 + $this->iBottomBorder / 3, $this->iXmax / 2, $this->vAxisColor);
            
            // Xmax
            imageline($this->vImage, $this->iCoordsXmax, $this->iCoordsY0, $this->iCoordsXmax, $this->iCoordsY0 + 5, $this->vAxisColor);
            ImageString($this->vImage, $this->iCoordsFont, $this->iCoordsXmax - 3, $this->iCoordsY0 + $this->iBottomBorder / 3, $this->iXmax, $this->vAxisColor);
        }
    }

    private function _label_CoordsX_Date($x, $time, $bans)
    {
        ImageString($this->vImage, $this->iCoordsFont, $this->iCoordsX0 + $x - 18, $this->iCoordsY0 + 7, substr($time, 0, 10), $this->vAxisColor);
        ImageString($this->vImage, $this->iCoordsFont, $this->iCoordsX0 + $x - 18, $this->iCoordsY0 + 17, substr($time, - 8), $this->vAxisColor);
        ImageString($this->vImage, $this->iCoordsFont, $this->iCoordsX0 + $x - 18, $this->iCoordsY0 + 27, "Bans: " . $bans, $this->vAxisColor);
        
        imageline($this->vImage, $this->iCoordsX0 + $x, $this->iCoordsY0, $this->iCoordsX0 + $x, $this->iCoordsY0 + 5, $this->vAxisColor);
    }

    private function _label_CoordsY()
    {
        $y_gap = 0;
        if ($this->iYmax != 0) {
            $y_gap = ($this->iCoordsY0 - $this->iTopBorder) / $this->iYmax;
        }
        
        // TODO one for all
        if ($this->iYmax <= 5) {
            for ($i = 0; $i <= $this->iYmax; $i ++) {
                
                // if($i % 1 == 0){
                imageline($this->vImage, $this->iCoordsX0 - 5, $this->iCoordsY0 - $i * $y_gap, $this->iCoordsX0, $this->iCoordsY0 - $i * $y_gap, $this->vAxisColor);
                imagestring($this->vImage, $this->iCoordsFont, $this->iCoordsX0 - 20, $this->iCoordsY0 - $i * $y_gap - 2 * $this->iCoordsLableFont, $i, $this->vAxisColor);
                // }
            }
        } elseif ($this->iYmax <= 10) {
            for ($i = 0; $i <= $this->iYmax; $i ++) {
                
                if ($i % 2 == 0) {
                    imageline($this->vImage, $this->iCoordsX0 - 5, $this->iCoordsY0 - $i * $y_gap, $this->iCoordsX0, $this->iCoordsY0 - $i * $y_gap, $this->vAxisColor);
                    imagestring($this->vImage, $this->iCoordsFont, $this->iCoordsX0 - 20, $this->iCoordsY0 - $i * $y_gap - 2 * $this->iCoordsLableFont, $i, $this->vAxisColor);
                }
            }
        } elseif ($this->iYmax <= 20) {
            for ($i = 0; $i <= $this->iYmax; $i ++) {
                
                if ($i % 5 == 0) {
                    imageline($this->vImage, $this->iCoordsX0 - 5, $this->iCoordsY0 - $i * $y_gap, $this->iCoordsX0, $this->iCoordsY0 - $i * $y_gap, $this->vAxisColor);
                    imagestring($this->vImage, $this->iCoordsFont, $this->iCoordsX0 - 20, $this->iCoordsY0 - $i * $y_gap - 2 * $this->iCoordsLableFont, $i, $this->vAxisColor);
                }
            }
        } elseif ($this->iYmax <= 50) {
            for ($i = 0; $i <= $this->iYmax; $i ++) {
                
                if ($i % 10 == 0) {
                    imageline($this->vImage, $this->iCoordsX0 - 5, $this->iCoordsY0 - $i * $y_gap, $this->iCoordsX0, $this->iCoordsY0 - $i * $y_gap, $this->vAxisColor);
                    imagestring($this->vImage, $this->iCoordsFont, $this->iCoordsX0 - 20, $this->iCoordsY0 - $i * $y_gap - 2 * $this->iCoordsLableFont, $i, $this->vAxisColor);
                }
            }
        } elseif ($this->iYmax <= 100) {
            for ($i = 0; $i <= $this->iYmax; $i ++) {
                
                if ($i % 20 == 0) {
                    imageline($this->vImage, $this->iCoordsX0 - 5, $this->iCoordsY0 - $i * $y_gap, $this->iCoordsX0, $this->iCoordsY0 - $i * $y_gap, $this->vAxisColor);
                    imagestring($this->vImage, $this->iCoordsFont, $this->iCoordsX0 - 20, $this->iCoordsY0 - $i * $y_gap - 2 * $this->iCoordsLableFont, $i, $this->vAxisColor);
                }
            }
        } elseif ($this->iYmax <= 200) {
            for ($i = 0; $i <= $this->iYmax; $i ++) {
                
                if ($i % 50 == 0) {
                    imageline($this->vImage, $this->iCoordsX0 - 5, $this->iCoordsY0 - $i * $y_gap, $this->iCoordsX0, $this->iCoordsY0 - $i * $y_gap, $this->vAxisColor);
                    imagestring($this->vImage, $this->iCoordsFont, $this->iCoordsX0 - 25, $this->iCoordsY0 - $i * $y_gap - 2 * $this->iCoordsLableFont, $i, $this->vAxisColor);
                }
            }
        } elseif ($this->iYmax <= 500) {
            for ($i = 0; $i <= $this->iYmax; $i ++) {
                
                if ($i % 100 == 0) {
                    imageline($this->vImage, $this->iCoordsX0 - 5, $this->iCoordsY0 - $i * $y_gap, $this->iCoordsX0, $this->iCoordsY0 - $i * $y_gap, $this->vAxisColor);
                    imagestring($this->vImage, $this->iCoordsFont, $this->iCoordsX0 - 25, $this->iCoordsY0 - $i * $y_gap - 2 * $this->iCoordsLableFont, $i, $this->vAxisColor);
                }
            }
        } elseif ($this->iYmax <= 1000) {
            for ($i = 0; $i <= $this->iYmax; $i ++) {
                
                if ($i % 200 == 0) {
                    imageline($this->vImage, $this->iCoordsX0 - 5, $this->iCoordsY0 - $i * $y_gap, $this->iCoordsX0, $this->iCoordsY0 - $i * $y_gap, $this->vAxisColor);
                    imagestring($this->vImage, $this->iCoordsFont, $this->iCoordsX0 - 25, $this->iCoordsY0 - $i * $y_gap - 2 * $this->iCoordsLableFont, $i, $this->vAxisColor);
                }
            }
        } else {
            // Ymax TODO Ymax > 1000 or all in one
            imageline($this->vImage, $this->iCoordsX0, $this->iCoordsYmax, $this->iCoordsX0 - 5, $this->iCoordsYmax, $this->vAxisColor);
            ImageString($this->vImage, $this->iCoordsFont, $this->iCoordsX0 - $this->iLeftBorder * 3 / 4, $this->iCoordsYmax - 6, $this->iYmax, $this->vAxisColor);
        }
    }

    private function _plot_Graph()
    {
        if (is_int($this->iXmax)) {
            // TODO
        } elseif (True) { // TODO Date-Time
            
            if($this->iDataCount == 0 ){
                $this->iDataCount = 1;
            }
            
            $x_gap = $this->iCoordsXlength / $this->iDataCount;
            $x = 0;
            $x_pre = 0;
            
            $y_factor = 0;
            if ($this->iYmax != 0) {
                $y_factor = $this->iCoordsYlength / $this->iYmax;
            }
            $y_pre = 0;
            
            $count = 0;
            $label_count = 0;
            
            // for html image map
            $bans_pre = - 1;
            $time_pre = "0000-00-00 00:00:00";
            
            foreach ($this->aData as $time => $bans) {
                
                $y = $bans * $y_factor;
                
                $this->_plot_XY($x, $y);
                
                // html image map
                if ($bans != $bans_pre || $count == $this->iDataCount - 1) {
                    $this->_map_XY($x, $y, $time . ', Bans: ' . $bans);
                    
                    if ($x_pre + 1 != $x) {
                        $this->_map_XY($x_pre, $y_pre, $time_pre . ', Bans: ' . $bans_pre);
                    }
                }
                
                // first line
                if ($x != 0) {
                    $this->_plot_Line($x, $y, $x_pre, $y_pre);
                }
                
                // label x-axis
                if ($count < 1 || $count > $this->iDataCount / 3 && $label_count == 1 || $count > 2 * $this->iDataCount / 3 && $label_count == 2 || $count == $this->iDataCount - 1) {
                    
                    $this->_label_CoordsX_Date($x, $time, $bans);
                    $label_count ++;
                }
                
                $x_pre = $x;
                $y_pre = $y;
                $bans_pre = $bans;
                $time_pre = $time;
                
                $x = $x + $x_gap;
                $count ++;
            }
        }
    }

    private function _plot_XY(int $X, int $Y, $tag = "")
    {
        $x = $this->iCoordsX0 + $X;
        $y = $this->iCoordsY0 - $Y;
        
        imagesetpixel($this->vImage, $x, $y, $this->vGraphColor);
    }

    private function _map_XY(int $X, int $Y, $tag = "")
    {
        $x = $this->iCoordsX0 + $X;
        $y = $this->iCoordsY0 - $Y;
        
        imageellipse($this->vImage, $x, $y, 3, 3, $this->vGraphColor);
        
        $this->sHtmlMap .= '<area shape="circle" coords="' . $x . ',' . $y . ',6"';
        $this->sHtmlMap .= ' href="" alt="' . $tag . '" title="' . $tag . '">';
        $this->sHtmlMap .= PHP_EOL;
    }

    private function _plot_Line(int $X1, int $Y1, int $X2, int $Y2)
    {
        imageline($this->vImage, $this->iCoordsX0 + $X1, $this->iCoordsY0 - $Y1, $this->iCoordsX0 + $X2, $this->iCoordsY0 - $Y2, $this->vGraphColor);
    }
}

?>
