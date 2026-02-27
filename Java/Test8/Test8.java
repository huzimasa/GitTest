public class Test8{
	public static void main(String[]args){
	
		//Calculationクラスのインスタンス
		Calculation calculation = new Calculation(1.5);
	
		//add メソッドに値を渡す
		calculation.add(2.5);
		//足し算の計算結果が出力される
		calculation.print();
		
		//sub メソッドに値を渡す
		calculation.sub(1.5);
		//引き算の計算結果が出力される
		calculation.print();
		
		//mul メソッド値を渡す
		calculation.mul(2.5);
		//掛け算の計算結果が出力される
		calculation.print();
		
		//div メソッドに値を渡す
		calculation.div(0.5);
		//割り算の計算結果が出力される
		calculation.print();
	}
}
