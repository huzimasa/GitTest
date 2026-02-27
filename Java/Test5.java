public class Test5{
	public static void main(String[] args){
		int [][] array = new int[9][9];
		for(int i = 0; i < 9; i++){
			for(int j = 0; j < 9; j++ ){
				// i と j に 1 を加算し、1×1 から開始させ 9×9で処理終了。
				array[i][j] = (i + 1) * (j + 1);
				System.out.println("array["+ i + "]" + "[" + j + "]：" + array[i][j]); //array[0][0]：1…方式
			}
			// 9回出力する度に改行
			System.out.println();
		}
	}
}
