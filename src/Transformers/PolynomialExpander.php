<?php

namespace Rubix\ML\Transformers;

use InvalidArgumentException;

/**
 * Polynomial Expander
 *
 * This Transformer will generate polynomial features up to and including the
 * specified degree. Polynomial expansion is often used to fit data that is
 * non-linear using a linear Estimator such as Ridge.
 *
 * @category    Machine Learning
 * @package     Rubix/ML
 * @author      Andrew DalPino
 */
class PolynomialExpander implements Transformer
{
    /**
     * The degree of the polynomials to generate. Higher order polynomials are
     * able to fit data better, however require extra features to be added
     * to the dataset.
     *
     * @var int
     */
    protected $degree;

    /**
     * @param  int  $degree
     * @throws \InvalidArgumentException
     * @return void
     */
    public function __construct(int $degree = 2)
    {
        if ($degree < 1) {
            throw new InvalidArgumentException('The degree of the polynomial'
                . ' must be greater than 0.');
        }

        $this->degree = $degree;
    }

    /**
     * Apply the transformation to the samples in the data frame.
     *
     * @param  array  $samples
     * @return void
     */
    public function transform(array &$samples) : void
    {
        $columns = count(reset($samples));

        foreach ($samples as &$sample) {
            $vector = [];

            for ($i = 0; $i < $columns; $i++) {
                for ($j = 1; $j <= $this->degree; $j++) {
                    $vector[] = $sample[$i] ** $j;
                }
            }

            $sample = $vector;
        }
    }
}
