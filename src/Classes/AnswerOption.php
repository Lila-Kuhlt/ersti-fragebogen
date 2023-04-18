<?php

namespace Kuhlt\ErstiFragebogen\Classes;

class AnswerOption
{
    const COURSE = [
        1 => [
            'id' => 1,
            'label' => 'Informatik Bachelor',
        ],
        2 => [
            'id' => 2,
            'label' => 'Informatik Master',
        ],
        3 => [
            'id' => 3,
            'label' => 'Informatik Lehramt',
        ],
        4 => [
            'id' => 4,
            'label' => 'Mathematik Bachelor',
        ],
        5 => [
            'id' => 5,
            'label' => 'Mathematik Master',
        ],
        6 => [
            'id' => 6,
            'label' => 'Mathematik Lehramt',
        ],
        7 => [
            'id' => 7,
            'label' => 'Wirtschaftsmathematik Bachelor',
        ],
        8 => [
            'id' => 8,
            'label' => 'Wirtschaftsmathematik Master',
        ],
        9 => [
            'id' => 9,
            'label' => 'Technomathematik Bachelor',
        ],
        10 => [
            'id' => 10,
            'label' => 'Sonstiges',
        ],
    ];

    const T_SHIRT = [
        0 => [
            'id' => 0,
            'label' => 'Kein T-Shirt',
            'price' => 0,
        ],
        1 => [
            'id' => 1,
            'label' => 'Lady-XS',
            'price' => 800,
        ],
        2 => [
            'id' => 2,
            'label' => 'Lady-S',
            'price' => 800,
        ],
        3 => [
            'id' => 3,
            'label' => 'Lady-M',
            'price' => 800,
        ],
        4 => [
            'id' => 4,
            'label' => 'Lady-L',
            'price' => 800,
        ],
        5 => [
            'id' => 5,
            'label' => 'Lady-XL',
            'price' => 800,
        ],
        6 => [
            'id' => 6,
            'label' => 'Lady-XXL',
            'price' => 800,
        ],
        7 => [
            'id' => 7,
            'label' => 'XS',
            'price' => 800,
        ],
        8 => [
            'id' => 8,
            'label' => 'S',
            'price' => 800,
        ],
        9 => [
            'id' => 9,
            'label' => 'M',
            'price' => 800,
        ],
        10 => [
            'id' => 10,
            'label' => 'L',
            'price' => 800,
        ],
        11 => [
            'id' => 11,
            'label' => 'XL',
            'price' => 800,
        ],
        12 => [
            'id' => 12,
            'label' => 'XXL',
            'price' => 800,
        ],
        13 => [
            'id' => 13,
            'label' => '3XL',
            'price' => 800,
        ],
    ];

    const SLEEPING_PLACE = [
        0 => [
            'id' => 0,
            'label' => 'Ich bin versorgt.',
            'description' => 'Ich habe einen Schlafplatz für mich, den ich auch spät am Abend von Karlsruhe aus gut erreichen kann.',
            'selected' => true,
        ],
        1 => [
            'id' => 1,
            'label' => 'Ich suche noch einen Schlafplatz.',
            'description' => 'Ich komme spät am Abend nicht einfach nach Hause und würde mich über ein Angebot zum Übernachten freuen.',
            'selected' => false,
        ],
        2 => [
            'id' => 2,
            'label' => 'Ich kann jemandem einen Schlafplatz anbieten.',
            'description' => 'Ich habe zu Hause etwas Platz übrig kann mir vorstellen, andere Erstis bei Sympathie für die Tage der O-Phase bei mir übernachten zu lassen.',
            'selected' => false,
        ],
    ];

    const COCKTAIL_PARTY = [
        2 => [
            'id' => 2,
            'label' => 'Ja, mit alkoholischen Cocktails, Bier und Fruchtsäften',
            'description' => 'Ich bin dabei und möchte auch alkoholische Cocktails trinken. Rechnet also sinnvolle Mengen für mich mit ein.',
            'price' => 1500,
            'selected' => true,
        ],
        1 => [
            'id' => 1,
            'label' => 'Ja, mit alkoholfreien Cocktails, Bier und Fruchtsäften',
            'description' => 'Ich bin dabei und möchte keine alkoholischen Cocktails trinken. Ihr könnt also weniger harten Alkohol und mehr Bier und Fruchtsäfte einkaufen.',
            'price' => 1000,
            'selected' => false,
        ],
        0 => [
            'id' => 0,
            'label' => 'Nein',
            'description' => 'Ich kann oder möchte nicht zur Cocktailfete kommen. Ihr könnt also weniger einkaufen.',
            'price' => 0,
            'selected' => false,
        ],
    ];

    const FRIDAY_SPECIAL = [
        1 => [
            'id' => 1,
            'label' => 'Bowling',
            'description' => 'Ich habe am Freitagabend Lust auf Bowling im LAGO Bowling Center.',
            'price' => 1200,
            'selected' => false,
        ],
        2 => [
            'id' => 2,
            'label' => 'Europabad',
            'description' => 'Ich möchte am Freitagabend mit ins Europabad.',
            'price' => 1000,
            'selected' => false,
        ],
        3 => [
            'id' => 3,
            'label' => 'Pauls Pirate Party',
            'description' => 'Ich möchte am Freitagabend direkt zu Pauls Pirate Party.',
            'price' => 0,
            'selected' => true,
        ],
    ];
}
