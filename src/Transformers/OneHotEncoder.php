<?php

namespace Rubix\ML\Transformers;

use Rubix\ML\Datasets\Dataset;
use Rubix\ML\Datasets\DataFrame;
use RuntimeException;

/**
 * One Hot Encoder
 *
 * The One Hot Encoder takes a column of categorical features and produces a n-d
 * one-hot (numerical) representation where n is equal to the number of unique
 * categories in that column. A 0 indicates that a category is not present in the
 * sample whereas a 1 indicates that a category is present.
 *
 * @category    Machine Learning
 * @package     Rubix/ML
 * @author      Andrew DalPino
 */
class OneHotEncoder implements Transformer, Stateful
{
    /**
     * The set of unique possible categories of the training set.
     *
     * @var array|null
     */
    protected $categories;

    /**
     * Fit the transformer to the dataset.
     *
     * @param  \Rubix\ML\Datasets\Dataset  $dataset
     * @return void
     */
    public function fit(Dataset $dataset) : void
    {
        $this->categories = [];

        $position = 0;

        foreach ($dataset->types() as $column => $type) {
            if ($type === DataFrame::CATEGORICAL) {
                $categories = [];

                foreach ($dataset as $sample) {
                    $category = $sample[$column];

                    if (!isset($categories[$category])) {
                        $categories[$category] = $position++;
                    }
                }

                $this->categories[$column] = $categories;
            }
        }
    }

    /**
     * Apply the transformation to the samples in the data frame.
     *
     * @param  array  $samples
     * @throws \RuntimeException
     * @return void
     */
    public function transform(array &$samples) : void
    {
        if (is_null($this->categories)) {
            throw new RuntimeException('Transformer has not been fitted.');
        }

        foreach ($samples as &$sample) {
            $vector = [];

            foreach ($this->categories as $column => $categories) {
                $temp = array_fill_keys($categories, 0);

                $category = $sample[$column];

                if (isset($categories[$category])) {
                    $position = $categories[$category];

                    $temp[$position] = 1;
                }

                $vector = array_merge($vector, $temp);

                unset($sample[$column]);
            }

            $sample = array_merge($sample, $vector);
        }
    }
}
