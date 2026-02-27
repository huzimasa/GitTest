//インポート
import java.util.ArrayList;
import java.util.List;

public class Test9_2{
	public static void main(String[] args){
		
		List<List<Integer>> result = new ArrayList<>();
		// 九九の計算
		for(int i = 1; i <= 9; i++){
			//１の段から段ごとに格納するためのList calculation を宣言
			List<Integer> calculation = new ArrayList<Integer>();
			for(int j = 1; j <= 9; j++ ){
				//結果を calculation(計算結果追加用)に追加
				calculation.add((i * j));
			}
			//calculation(計算結果追加用)を result に追加
			result.add(calculation);
		}

		for(int i = 0; i < result.size(); i++){
			for(int j = 0; j < result.get(i).size(); j++){
				System.out.println("List[" + i + "]" + "[" + j + "]："  + result.get(i).get(j));
			}
			System.out.println();
		}
	}
}
