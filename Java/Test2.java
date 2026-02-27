public class Test2{
	public static void main(String[] args){
		//配列arrayにA,B,C,Dを格納
		String[] array = {"A","B","C","D"};

		//配列arrayから1つずつ出力(\"で " を文字列に)
		for(int i = 0; i < array.length; i++){
			System.out.println("配列の" + i + "番目：\""+ array[i] + "\"");
		}
	}
}
