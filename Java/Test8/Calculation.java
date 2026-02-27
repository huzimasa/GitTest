/*---------------------------------------------------------------------------------------------------------------------------------------------------------
*ここから問８ 作成したクラスを小数点を含む数値にも対応させてください。
*----------------------------------------------------------------------------------------------------------------------------------------------------------*/

public class Calculation {
	//double型 のフィールド変数
	double value;
	
	//double 型の初期値を受け取り value に初期値を保持するコンストラクタ
	Calculation(double number) {
		System.out.println("初期値は" + number);
		//見やすくするため空行追加
		System.out.println();
		this.value = number;
	}
	
	//【メソッド１】 value の値と引数 add を足し算 valueに代入
	public void add(double addNumber) {
		System.out.println("足し算の引数は" + addNumber);
		this.value += addNumber;
	}
	
	//【メソッド2】 value の値と引数 sub を引き算 valueに代入
	public void sub(double subNumber) {
		System.out.println("引き算の引数は" + subNumber);
		this.value -= subNumber;
	}
	
	//【メソッド3】 value の値と引数 mul を掛け算 valueに代入
	public void mul(double mulNumber) {
		System.out.println("掛け算の引数は" + mulNumber);
		this.value *= mulNumber;
	}
	
	//【メソッド4】 value の値と引数 div を割り算 valueに代入
	public void div(double divNumber) {
		//計算時に0で割るとエラーとして出力し、メソッドを終了させる
		if(divNumber == 0) {
			System.out.println("エラー：0で割ることはできません。");
			System.exit(1);
		}
		this.value /= divNumber;
		System.out.println("割り算の引数は" + divNumber);
	}
	
	//【メソッド5】 計算結果を出力する引数なしのメソッド
	public void print() {
		System.out.println("現在の計算結果は：" + this.value);
		//見やすくするため空行追加
		System.out.println();
	}
}
