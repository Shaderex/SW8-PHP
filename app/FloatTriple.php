<?php

namespace DataCollection;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;

/**
 * DataCollection\FloatTriple
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $compressed_data
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\FloatTriple whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\FloatTriple whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\FloatTriple whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\DataCollection\FloatTriple whereCompressedData($value)
 * @mixin \Eloquent
 */
class FloatTriple extends Model
{

    private static $DECIMAL_PRECISION = 0b10;
    private static $BITS_PER_VALUE = 19;
    private static $FIRST_VALUE = 2;
    private static $SECOND_VALUE = 1;
    private static $THIRD_VALUE = 0;
    private static $VALUE_MASK = 0b0000000000000000000000000000000000000000000001111111111111111111;
    private static $SIGN_MASK = 0b10000000000000000000000000000000000000000000000000000000000000000;
    private static $DECIMAL_POSITION = 2;

    public function getCompressedData()
    {
        return $this->compressed_data;
    }

    public function setCompressedData($floats)
    {
        if (is_array($floats) && count($floats) == 3) {
            foreach ($floats as $i => $float) {
                if (!is_float($float)) {
                    throw new InvalidArgumentException("The " . $i . "th element is not a float.");
                }

                $this->compressed_data = $this->compressValues($floats);

            }
        } else {
            throw new InvalidArgumentException("The arguments given is not an array of three floats");
        }
    }

    private function compressValues($floats)
    {
        $compressedValue = 0b0;

        $compressedValue = $compressedValue | FloatTriple::$DECIMAL_PRECISION;
        foreach ($floats as $float) {
            $compressedValue = $compressedValue << 1;

            if ($float < 0) {
                $compressedValue = $compressedValue | 0b1;
                $float *= -1; // From now on we only want positive numbers
            }

            $precision = 7 - FloatTriple::$DECIMAL_PRECISION - 1;
            $binaryEncoding = round($float * pow(10, $precision));

            if ($binaryEncoding > 0b1111111111111111111) {
                throw new InvalidArgumentException("Cannot compress value, the float seen as an integer is larger than 52487");
            }

            $compressedValue = $compressedValue << FloatTriple::$BITS_PER_VALUE;

            $compressedValue = $compressedValue | $binaryEncoding;
        }

        return $compressedValue;

    }

    public function getFirstValue()
    {
        return $this->decompressValue(FloatTriple::$FIRST_VALUE);
    }

    public function getSecondValue()
    {
        return $this->decompressValue(FloatTriple::$SECOND_VALUE);
    }

    public function getThirdValue()
    {
        return $this->decompressValue(FloatTriple::$THIRD_VALUE);
    }

    private function decompressValue($valuePosition)
    {
        $shiftAmount = 20 * $valuePosition;

        // Create a bitmask the finds 20 bits encoding the float
        $valueMask = 0b11111111111111111111 << $shiftAmount;

         // Apply value mask and shift to get the value as an integer
         $valueData = ($this->compressed_data & $valueMask) >> $shiftAmount;

         // Apply mask to find the actual value of the float (ignoring signing)
        $value = (float) ($valueData & FloatTriple::$VALUE_MASK);

         // Apply mask to find the sign bit and move it to the first position (if this value is 1 then the number is negative)
        $isNegative = (($valueData & FloatTriple::$SIGN_MASK) >> FloatTriple::$BITS_PER_VALUE) == 0b00000000000000000000000000000000000000000000000000000000000000001;

         if ($isNegative) {
             $value *= -1;
         }

         // Move the decimal point to the correct place in the float
         $precision = 7 - FloatTriple::$DECIMAL_POSITION - 1;
         $value =  $value / pow(10, $precision);

    return $value;
    }


}
