<?php

namespace OrderBundle\Test\Service;

use OrderBundle\Repository\BadWordsRepository;
use OrderBundle\Service\BadWordsValidator;
use PHPUnit\Framework\TestCase;

class BadWordsValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider badWordsDataProvider
     */
    public function hasBadWords($badWordsList, $text, $foundBadWords)
    {
        $badWordsRepository = $this->createMock(BadWordsRepository::class);

        $badWordsRepository->method('findAllAsArray')->willReturn($badWordsList);

        $badWordsValidator = new BadWordsValidator($badWordsRepository);

        $hasBadWords = $badWordsValidator->hasBadWords($text);

        $this->assertEquals($foundBadWords, $hasBadWords);
    }

    public static function badWordsDataProvider()
    {
        $badWordsList = [fake()->word(), fake()->word(), fake()->word()];
        $badText = fake()->text(25) . ' ' . fake()->randomElement($badWordsList);
        $cleanText = fake()->text(5);

        return [
            'shouldFindWhenHasBadWords' => [
                'badWordsList' => $badWordsList,
                'text' => $badText,
                'foundBadWords' => true
            ],
            'shouldNotFindWhenHasNoBadWords' => [
                'badWordsList' => $badWordsList,
                'text' => $cleanText,
                'foundBadWords' => false
            ],
            'shouldNotFindWhenTextIsEmpty' => [
                'badWordsList' => $badWordsList,
                'text' => '',
                'foundBadWords' => false
            ],
            'shouldNotFindWhenBadWordsListIsEmpty' => [
                'badWordsList' => [],
                'text' => $badText,
                'foundBadWords' => false
            ]
        ];
    }
}