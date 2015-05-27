<?php
/**
 * @var User $user
 */
use user\models\User;
$key = substr(md5($user->RunetId.'AFWf4BwXVXpMUblVQDICoUz0'), 0, 16);
$goldenSiteRegLink = "http://2014.goldensite.ru/personal/introduce/?RUNETID=" . $user->RunetId . "&KEY=" . $key;



$hash = substr(md5('fiNAQ3t32RYn9HTGkEdKzRrYS'.$user->RunetId), 1, 16);
$mbltDevRegLink = sprintf('http://mbltdev.ru/?RunetId=%s&Hash=%s', $user->RunetId, $hash);

?>
<h3><?=$user->getShortName();?>, здравствуйте!</h3>

<p style="font-size: 120%;">Октябрь этого года полон интересных мероприятий: выставки, форумы, конференции, премии. Мы отобрали самые значимые мероприятия, которые не должны пройти для Вас незамеченными.</p>

<hr style="border: 0; border-top: 1px solid #DDDDDD; height: 1px; margin: 50px 0; width: 100%;" />

<table style="border: 0; border-collapse: collapse; margin: 25px 0; width: 100%;">
	<tr>
		<td style="border: 0; padding: 10px; vertical-align: top;">
			<h1>Конференция<br/>Performance Marketing Moscow</h1>
		</td>
		<td style="border: 0; padding: 10px; vertical-align: top; width: 200px;">
			<img src="http://runet-id.com/files/event/pm14/120.png" style="border: 0; height: auto; width: 120px;" />
		</td>
	</tr>
	<tr>
		<td style="border: 0; padding: 10px; vertical-align: top;">
			<p>Конференция Performance Marketing Moscow посвящена стратегии построения эффективного присутствия брендов в интернете, а также инструментам, каналам и технологиям, которые позволяют обеспечить максимальный ROI для бизнеса.</p>
			<p>Участники «Performance Marketing Moscow» - это лидеры российского бизнеса и маркетинг директора ТОП-100 компаний-рекламодателей в России.</p>
			<p>На конференции выступят только 12 спикеров, представители топ-менеджмента крупнейших международных и российских компаний.</p>
		</td>
		<td style="border: 0; padding: 10px; vertical-align: top; width: 200px;">
			<h4>15 октября 2014 г.</h4>
			<p><b>Место проведения:</b><br/>г. Москва, Малый Конюшковский пер., д. 2, MOD Design</p>
			<p><a href="http://pm-moscow.com/" target="_blank">www.pm-moscow.com</a></p>
		</td>
	</tr>
	<tr>
		<td style="background: #E1F4FD; border: 0; padding: 15px 20px; vertical-align: middle;">
			<b>Принять участие в Performance Marketing Moscow</b>
		</td>
		<td style="background: #E1F4FD; border: 0; padding: 15px 10px; text-align: center; vertical-align: middle; width: 200px;">
			<a href="<?=$user->getFastauthUrl('http://runet-id.com/event/pm14/');?>" style="display: inline-block; background: #3B3B3B; color: #ffffff; padding: 10px 20px; border-radius: 4px; border-top: 1px solid #737373; border-left: 1px solid #737373; text-decoration: none;">Регистрация</a>
		</td>
	</tr>
</table>

<hr style="border: 0; border-top: 1px solid #DDDDDD; height: 1px; margin: 50px 0; width: 100%;" />

<table style="border: 0; border-collapse: collapse; margin: 25px 0; width: 100%;">
	<tr>
		<td style="border: 0; padding: 10px; vertical-align: top;">
			<h1>Конференция<br/>User eXperience 2014</h1>
		</td>
		<td style="border: 0; padding: 10px; vertical-align: top; width: 200px;">
			<img src="http://runet-id.com/files/event/userexp2014/120.png" style="border: 0; height: auto; width: 120px;" />
		</td>
	</tr>
	<tr>
		<td style="border: 0; padding: 10px; vertical-align: top;">
			<p>User eXperience 2014 - восьмая международная профессиональная конференция, посвященная вопросам юзабилити и User Experience. В программе лучшие доклады экспертов, мастер-классы профессионалов высочайшего уровня, неформальная площадка для общения друзей и коллег.</p>
			<p>В этом году пора выйти за привычные рамки. Как вы можете привлечь людей к использованию продуктов или сервисов? Какой тип дизайн-мышления необходимо применять, чтобы люди оставались вовлечёнными? Как вы можете увлечь тех, кто не является профессионалом в нашей области, чтобы донести важность положительного опыта пользователя? Можем ли мы вовлечь людей с целью изменить то, что сейчас работает не так хорошо, как хотелось бы?</p>
		</td>
		<td style="border: 0; padding: 10px; vertical-align: top; width: 200px;">
			<h4>23-24 октября 2014 г.</h4>
			<p><b>Место проведения:</b><br/>г. Москва, Ленинградский проспект,, д. 39, стр. 79, Медиа-центр Mail.Ru Group</p>
			<p><a href="http://2014.userexperience.ru" target="_blank">2014.userexperience.ru</a></p>
		</td>
	</tr>
	<tr>
		<td style="background: #E1F4FD; border: 0; padding: 15px 20px; vertical-align: middle;">
			<b>Принять участие в User eXperience 2014</b>
		</td>
		<td style="background: #E1F4FD; border: 0; padding: 15px 10px; text-align: center; vertical-align: middle; width: 200px;">
			<a href="<?=$user->getFastauthUrl('http://runet-id.com/event/userexp2014/');?>" style="display: inline-block; background: #3B3B3B; color: #ffffff; padding: 10px 20px; border-radius: 4px; border-top: 1px solid #737373; border-left: 1px solid #737373; text-decoration: none;">Регистрация</a>
		</td>
	</tr>
</table>

<hr style="border: 0; border-top: 1px solid #DDDDDD; height: 1px; margin: 50px 0; width: 100%;" />

<table style="border: 0; border-collapse: collapse; margin: 25px 0; width: 100%;">
	<tr>
		<td style="border: 0; padding: 10px; vertical-align: top;">
			<h1>Конференция<br/>#MBLTdev</h1>
		</td>
		<td style="border: 0; padding: 10px; vertical-align: top; width: 200px;">
			<img src="http://runet-id.com/files/event/mbltdev14/120.png" style="border: 0; height: auto; width: 120px;" />
		</td>
	</tr>
	<tr>
		<td style="border: 0; padding: 10px; vertical-align: top;">
			<p>Уникальное профессиональное мероприятие для разработчиков мобильных приложений.</p>
			<p>Функциональное тестирование Android приложений, программирование на Swift, современные способы аутентификации, роль носимых гаджетов в безопасности, архитектура различных приложений, iBeacon, безопасность iOS-устройств и как их могут взломать – об этом и многом другом расскажут спикеры на первой международной конференции мобильных разработчиков #MBLTDev.</p>
			<p>Конференция соберет экспертов со всех концов света, от Филиппин до США для обмена опытом и последними тенденциями в области мобильной разработки.</p>
		</td>
		<td style="border: 0; padding: 10px; vertical-align: top; width: 200px;">
			<h4>28 октября 2014 г.</h4>
			<p><b>Место проведения:</b><br/>г. Москва, Берсеневская набережная, д. 6, стр. 3, Digital October</p>
			<p><a href="http://mbltdev.ru/" target="_blank">www.mbltdev.ru</a></p>
		</td>
	</tr>
	<tr>
		<td style="background: #E1F4FD; border: 0; padding: 15px 20px; vertical-align: middle;">
			<b>Принять участие в #MBLTdev</b>
		</td>
		<td style="background: #E1F4FD; border: 0; padding: 15px 10px; text-align: center; vertical-align: middle; width: 200px;">
			<a href="<?=$mbltDevRegLink;?>" style="display: inline-block; background: #3B3B3B; color: #ffffff; padding: 10px 20px; border-radius: 4px; border-top: 1px solid #737373; border-left: 1px solid #737373; text-decoration: none;">Регистрация</a>
		</td>
	</tr>
</table>

<hr style="border: 0; border-top: 1px solid #DDDDDD; height: 1px; margin: 50px 0; width: 100%;" />

<table style="border: 0; border-collapse: collapse; margin: 25px 0; width: 100%;">
	<tr>
		<td style="border: 0; padding: 10px; vertical-align: top;">
			<h1>Конференция<br/>Форум малого бизнеса IPLACE 4</h1>
		</td>
		<td style="border: 0; padding: 10px; vertical-align: top; width: 200px;">
			<img src="http://runet-id.com/files/event/iplaceconf14/120.png" style="border: 0; height: auto; width: 120px;" />
		</td>
	</tr>
	<tr>
		<td style="border: 0; padding: 10px; vertical-align: top;">
			<p>Форум IPLACE является уникальным мероприятием, так как носит узкоспециализированный характер.</p>
			<p>Данное мероприятие бесплатно для участников и проводится с целью повышения компетенций специалистов предприятий малого бизнеса в сфере интернет-продаж.</p>
			<p>Форум IPLACE – это эффективная площадка для получения актуальных знаний, обмена опытом и профессионального общения.</p>
		</td>
		<td style="border: 0; padding: 10px; vertical-align: top; width: 200px;">
			<h4>14 октября 2014 г.</h4>
			<p><b>Место проведения:</b><br/>г. Санкт-Петербург, Лермонтовский проспект, д. 43/1, Отель Азимут</p>
			<p><a href="http://iplaceconf.ru/" target="_blank">www.iplaceconf.ru</a></p>
		</td>
	</tr>
	<tr>
		<td style="background: #E1F4FD; border: 0; padding: 15px 20px; vertical-align: middle;">
			<b>Принять участие в IPLACE 4</b>
		</td>
		<td style="background: #E1F4FD; border: 0; padding: 15px 10px; text-align: center; vertical-align: middle; width: 200px;">
			<a href="http://iplaceconf.ru/login" style="display: inline-block; background: #3B3B3B; color: #ffffff; padding: 10px 20px; border-radius: 4px; border-top: 1px solid #737373; border-left: 1px solid #737373; text-decoration: none;">Регистрация</a>
		</td>
	</tr>
</table>

<hr style="border: 0; border-top: 1px solid #DDDDDD; height: 1px; margin: 50px 0; width: 100%;" />

<table style="border: 0; border-collapse: collapse; margin: 25px 0; width: 100%;">
	<tr>
		<td style="border: 0; padding: 10px; vertical-align: top;">
			<h1>Конференция<br/>EdCrunch</h1>
		</td>
		<td style="border: 0; padding: 10px; vertical-align: top; width: 200px;">
			<img src="http://runet-id.com/files/event/edcrunch14/120.png" style="border: 0; height: auto; width: 120px;" />
		</td>
	</tr>
	<tr>
		<td style="border: 0; padding: 10px; vertical-align: top;">
			<p>Первая международная конференция, посвященная новым образовательным технологиям.</p>
			<p>В конференции примут участие основатели и руководители ведущих образовательных платформ - Дафна Коллер (Coursera), Анант Агарвал (edX), Ханс Клеппер (Iversity), Сотирис Макригианнис (Eliademy), а также глава штаб-квартиры Knewton в Европе Чарли Харрингтон и другие эксперты.</p>
		</td>
		<td style="border: 0; padding: 10px; vertical-align: top; width: 200px;">
			<h4>17-18 октября 2014 г.</h4>
			<p><b>Место проведения:</b><br/>г. Москва, Берсеневская набережная, д. 6, стр. 3, Digital October</p>
			<p><a href="http://www.edcrunch.ru" target="_blank">www.edcrunch.ru</a></p>
		</td>
	</tr>
	<tr>
		<td style="background: #E1F4FD; border: 0; padding: 15px 20px; vertical-align: middle;">
			<b>Принять участие в EdCrunch</b>
		</td>
		<td style="background: #E1F4FD; border: 0; padding: 15px 10px; text-align: center; vertical-align: middle; width: 200px;">
			<a href="<?=$user->getFastauthUrl('http://runet-id.com/event/edcrunch14/');?>" style="display: inline-block; background: #3B3B3B; color: #ffffff; padding: 10px 20px; border-radius: 4px; border-top: 1px solid #737373; border-left: 1px solid #737373; text-decoration: none;">Регистрация</a>
		</td>
	</tr>
</table>

<hr style="border: 0; border-top: 1px solid #DDDDDD; height: 1px; margin: 50px 0; width: 100%;" />

<table style="border: 0; border-collapse: collapse; margin: 25px 0; width: 100%;">
	<tr>
		<td style="border: 0; padding: 10px; vertical-align: top;">
			<h1>Конференция<br/>Generation NEXT.<br/>Дети 2014</h1>
		</td>
		<td style="border: 0; padding: 10px; vertical-align: top; width: 200px;">
			<img src="http://runet-id.com/files/event/next2014/120.png" style="border: 0; height: auto; width: 120px;" />
		</td>
	</tr>
	<tr>
		<td style="border: 0; padding: 10px; vertical-align: top;">
			<p>Конференция призвана поднять главные контентные, этические, регуляторные вопросы развития детского сегмента Рунета, а также бизнес-ориентированные аспекты и вопросы маркетинга в интернете для несовершеннолетних пользователей. </p>
			<p>Ведущие эксперты и практики обсудят все актуальные вопросы в области детского интернета и маркетинга в сети.</p>
		</td>
		<td style="border: 0; padding: 10px; vertical-align: top; width: 200px;">
			<h4>27 октября 2014 г.</h4>
			<p><b>Место проведения:</b><br/>г. Москва, Тверская улица д. 7,  DI Telegraph</p>
			<p><a href="http://runet-id.com/event/next2014/" target="_blank">www.runet-id.com</a></p>
		</td>
	</tr>
	<tr>
		<td style="background: #E1F4FD; border: 0; padding: 15px 20px; vertical-align: middle;">
			<b>Принять участие в Generation NEXT. Дети 2014</b>
		</td>
		<td style="background: #E1F4FD; border: 0; padding: 15px 10px; text-align: center; vertical-align: middle; width: 200px;">
			<a href="<?=$user->getFastauthUrl('http://runet-id.com/event/next2014/');?>" style="display: inline-block; background: #3B3B3B; color: #ffffff; padding: 10px 20px; border-radius: 4px; border-top: 1px solid #737373; border-left: 1px solid #737373; text-decoration: none;">Регистрация</a>
		</td>
	</tr>
</table>

<hr style="border: 0; border-top: 1px solid #DDDDDD; height: 1px; margin: 50px 0; width: 100%;" />

<table style="border: 0; border-collapse: collapse; margin: 25px 0; width: 100%;">
	<tr>
		<td style="border: 0; padding: 10px; vertical-align: top;">
			<h1>Конкурс<br/>Золотой сайт</h1>
		</td>
		<td style="border: 0; padding: 10px; vertical-align: top; width: 200px;">
			<img src="http://2014.goldensite.ru/local/templates/goldensite/images/img_about_2.png" style="border: 0; height: auto; width: 75px;" />
		</td>
	</tr>
	<tr>
		<td style="border: 0; padding: 10px; vertical-align: top;">
			<p>Золотой Сайт – ключевой и старейший конкурс интернет-проектов в Рунете.</p>
			<p> С 1997 года независимое жюри конкурса, которое в разные годы возглавляли Артемий Лебедев, Антон Носик, Алекс Экслер, Сергей Плуготаренко и другие звезды Рунета, вручает заветные статуэтки Золотого Кибермастера наиболее достойным проектам.</p>
		</td>
		<td style="border: 0; padding: 10px; vertical-align: top; width: 200px;">
			<h4>24 октября 2014 г.</h4>
			<p><b>Окончание приема работ на конкурс.</b><br/>Успейте подать заявку!</p>
			<p><a href="http://2014.goldensite.ru/" target="_blank">2014.goldensite.ru</a></p>
		</td>
	</tr>
	<tr>
		<td style="background: #E1F4FD; border: 0; padding: 15px 20px; vertical-align: middle;">
			<b>Участвовать в конкурсе Золотой сайт</b>
		</td>
		<td style="background: #E1F4FD; border: 0; padding: 15px 10px; text-align: center; vertical-align: middle; width: 200px;">
			<a href="<?=$goldenSiteRegLink;?>" style="display: inline-block; background: #3B3B3B; color: #ffffff; padding: 10px 20px; border-radius: 4px; border-top: 1px solid #737373; border-left: 1px solid #737373; text-decoration: none;">Подать сайт</a>
		</td>
	</tr>
</table>
