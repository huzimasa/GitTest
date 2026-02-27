document.addEventListener("DOMContentLoaded", function() {
	// JavaScript(ブラウザの標準機能で自然言語処理)
	function getCharCount(str) {
		return [...new Intl.Segmenter("ja", { granularity: "grapheme" }).segment(str)].length;
	}

	function removeEmojis(str) {
		// Unicode 絵文字の範囲にマッチする正規表現
		return str.replace(/[\p{Extended_Pictographic}]/gu, "");
	}

	function setupCharacterCount(inputId, maxLength, displayName) {
		const inputField = document.getElementById(inputId);
		if (!inputField) return;

		let errorSpan = document.createElement("span");
		errorSpan.className = "error-message";
		errorSpan.style.color = "red";
		errorSpan.style.display = "block";
		inputField.parentNode.appendChild(errorSpan);

		const counter = document.createElement("span");
		counter.id = `${inputId}-counter`;
		counter.style.color = "gray";
		counter.style.marginTop = "5px";
		counter.style.display = "block";
		inputField.parentNode.appendChild(counter);

		function updateCounter() {
			let cleanedValue = removeEmojis(inputField.value);
			if (cleanedValue !== inputField.value) {
				errorSpan.textContent = `「${displayName}」には絵文字を入力できません。`;
				inputField.value = cleanedValue; // 絵文字を削除
			} else {
				errorSpan.textContent = "";
			}

			const charCount = getCharCount(inputField.value);
			const remaining = maxLength - charCount;
			counter.textContent = `残り ${remaining} 文字`;

			if (remaining < 0) {
				errorSpan.textContent = `「${displayName}」は最大 ${maxLength} 文字まで入力可能です。`;
				inputField.value = [...new Intl.Segmenter("ja", { granularity: "grapheme" }).segment(inputField.value)]
					.slice(0, maxLength)
					.map(segment => segment.segment)
					.join("");
				counter.textContent = "残り 0 文字";
			}
		}

		inputField.addEventListener("input", updateCounter);
		updateCounter();
	}

	// フィールドの最大文字数設定
	const fieldSettings = {
		name: [100, "会員氏名"],
		address: [255, "住所"],
		email: [100, "メールアドレス"],
		notes: [100, "備考"],
		landline_phone: [13, "電話番号（固定）"],
		mobile_phone: [13, "電話番号（携帯）"],
		type_name: [10, "種別名"]
	};

	Object.entries(fieldSettings).forEach(([id, [maxLength, displayName]]) => {
		setupCharacterCount(id, maxLength, displayName);
	});

	// 電話番号のバリデーション
	function validateMemberForm() {
		const landlinePhone = document.getElementById("landline_phone")?.value.trim();
		const mobilePhone = document.getElementById("mobile_phone")?.value.trim();
		const errorContainer = document.getElementById("error-messages");

		if (!errorContainer) return true;

		errorContainer.innerHTML = "";
		let errors = [];

		if (landlinePhone === "" && mobilePhone === "") {
			errors.push("電話番号はどちらか入力してください。");
		}

		if (errors.length > 0) {
			errorContainer.innerHTML = `<p style="color: red;">${errors.join("<br>")}</p>`;
			return false;
		}

		return true;
	}

	const memberForm = document.getElementById("registrationForm");
	if (memberForm) {
		memberForm.onsubmit = validateMemberForm;
	}

	// 会員種別編集画面のバリデーション
	function validateMemberTypeForm() {
		const typeName = document.getElementById("type_name")?.value.trim();
		const errorContainer = document.getElementById("error-messages");

		if (!errorContainer) return true;

		errorContainer.innerHTML = "";
		let errors = [];

		if (typeName === "") {
			errors.push("「種別名」は必須入力です。");
		}

		if (errors.length > 0) {
			errorContainer.innerHTML = `<p style="color: red;">${errors.join("<br>")}</p>`;
			return false;
		}
		return true;
	}

	const memberTypeForm = document.getElementById("memberTypeForm");
	if (memberTypeForm) {
		memberTypeForm.onsubmit = validateMemberTypeForm;
	}
});
