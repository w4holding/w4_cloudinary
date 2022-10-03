<?php

namespace W4Services\W4Cloudinary\Overrides;

use TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder as Typo3TagBuilder;

use W4Services\W4Cloudinary\Constants;
use W4Services\W4Cloudinary\Traits\Configuration;

class TagBuilder extends Typo3TagBuilder {

    use Configuration;

    /**
     * @inheritdoc
     */
    public function addAttribute($attributeName, $attributeValue, $escapeSpecialCharacters = true) {

        if( 'img' === $this->getTagName()
        &&  'src' === $attributeName
        && !!$this->processViaJavascript()
        && TYPO3_MODE != 'BE') {

            $attributeName = 'data-src';

        }

        return parent::addAttribute(
            $attributeName,
            $attributeValue,
            $escapeSpecialCharacters
        );

    }

    public function render() {

        if( 'img' === $this->getTagName()
        && !!$this->processViaJavascript()) {

            $this->addAttribute(
                'class',
                join(
                    ' ',
                    array_merge(
                        [
                            Constants::CLOUDINARY_RESPONSIVE_CSS_CLASS
                        ],
                        explode( ' ', $this->getAttribute( 'class') ?: '')
                    )
                )
            );

        }

        return parent::render();

    }

}
