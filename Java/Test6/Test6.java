public class Test6{
	public static void main(String[]args){
		
		//Calculationクラスのインスタンスを初期値 10 として作成
		Calculation calculation = new Calculation(10);
		
		//add メソッドに 5の値を渡す
		calculation.add(5);
		//足し算の計算結果が出力される
		calculation.print();
		
		//sub メソッドに 5の値を渡す
		calculation.sub(5);
		//引き算の計算結果が出力される
		calculation.print();
		
		//mul メソッドに 2の値を渡す
		calculation.mul(2);
		//掛け算の計算結果が出力される
		calculation.print();
		
		//div メソッドに 2の値を渡す
		calculation.div(2);
		//割り算の計算結果が出力される
		calculation.print();
	}
}
