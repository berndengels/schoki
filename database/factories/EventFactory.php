<?php
/**
 * EventFactory.php
 *
 * @author    Bernd Engels
 * @created   07.05.19 16:47
 * @copyright Webwerk Berlin GmbH
 */

use Faker\Generator as Faker;
use App\Models\Mocks\EventMock;

$factory->define(EventMock::class, function (Faker $faker) {
	return [
		'title' 		=> $faker->sentence,
		'event_date'	=> $faker->date(),
		'description'	=> "<p>".implode("</p>\n\n<p>", $faker->paragraphs(rand(3,6)))."</p>",
	];
});
