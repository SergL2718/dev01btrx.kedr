/*
 * Изменено: 12 сентября 2021, воскресенье
 * Автор: Артамонов Денис <software.engineer@internet.ru>
 * copyright (c) 2021
 */

let questions = document.querySelectorAll('.faq .question');

for (let i in questions) {
	let question = questions[i];
	question.onclick = function () {
		let answer = this.nextElementSibling;
		answer.classList.toggle('show');
	}
}